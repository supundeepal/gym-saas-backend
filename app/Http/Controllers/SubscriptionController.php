<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gym;
use App\Models\Plan;
use App\Models\SubscriptionRequest;
use Carbon\Carbon;
use App\Models\SystemNotification;

class SubscriptionController extends Controller
{
    // 1. Gym Owner ගේ දැනට තියෙන Plan එක, ඉතුරු දවස් ගාණ සහ History එක යැවීම
    public function getMyPlan()
    {
        $user = Auth::user();
        
        // ජිම් එක සහ ඒකට අදාළ Plan එක ගන්නවා
        $gym = Gym::with('plan')->find($user->gym_id);
        
        $expireDate = Carbon::parse($user->expire_date);
        $today = Carbon::now();
        $daysLeft = (int) $today->diffInDays($expireDate, false); 

        $allPlans = Plan::all();

        // 🔴 මේ ජිම් එකෙන් කරපු Requests ටික අරන් එනවා (History එකට) 🔴
        $myRequests = SubscriptionRequest::with('plan')
                        ->where('gym_id', $user->gym_id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json([
            'status' => 'success',
            'current_plan' => $gym ? $gym->plan : null,
            'expire_date' => $expireDate->format('Y-m-d'),
            'days_left' => $daysLeft,
            'all_plans' => $allPlans,
            'requests' => $myRequests // 👈 මේකෙන් තමයි History එක Frontend එකට යන්නේ
        ]);
    }

    // 2. අලුත් මාසෙට සල්ලි ගෙවලා ස්ලිප් එක අප්ලෝඩ් කිරීම
    public function renewPlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required',
            'billing_cycle' => 'required|in:monthly,annually',
            'slip' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();
        
        // ස්ලිප් එක සේව් කරනවා
        $slipPath = $request->file('slip')->store('slips/subscriptions', 'public');

        SubscriptionRequest::create([
            'gym_id' => $user->gym_id,
            'plan_id' => $request->plan_id,
            'billing_cycle' => $request->billing_cycle,
            'slip_path' => $slipPath,
            'status' => 'pending'
        ]);

        // 🔴 Super Admin ට Notification එකක් යවනවා 🔴
        SystemNotification::create([
            'target_role' => 'super_admin',
            'title' => 'New SaaS Subscription Request',
            'message' => $user->name . ' has submitted a new subscription renewal request.'
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Renewal request sent successfully! Please wait for Super Admin approval.'
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Renewal request sent successfully! Please wait for Super Admin approval.'
        ]);
    }

    

    // 3. Super Admin ට - ඔක්කොම Pending Requests බලාගන්න
    public function getAllRequests()
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') return response()->json(['message' => 'Unauthorized Access.'], 403);

        $requests = SubscriptionRequest::with(['gym', 'plan'])->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'requests' => $requests]);
    }

    // 4. Super Admin ට - Request එකක් Approve කරන්න
    public function approveRequest($id)
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') return response()->json(['message' => 'Unauthorized Access.'], 403);

        $request = SubscriptionRequest::findOrFail($id);
        if ($request->status !== 'pending') return response()->json(['message' => 'Already processed'], 400);

        $request->status = 'approved';
        $request->save();

        // Gym එකේ Plan එක අලුත් කරනවා
        $gym = Gym::find($request->gym_id);
        if($gym) {
            $gym->plan_id = $request->plan_id;
            $gym->save();
        }

        // Owner ගේ Expire Date එක අප්ඩේට් කරනවා
        $owner = \App\Models\User::where('gym_id', $request->gym_id)->whereIn('role', ['owner', 'gym_owner', 'admin'])->first();
        if ($owner) {
            $currentExpire = Carbon::parse($owner->expire_date);
            $now = Carbon::now();
            
            // Monthly නම් 30ක්, Annually නම් 365ක් දෙනවා
            $daysToAdd = ($request->billing_cycle === 'annually') ? 365 : 30;
            
            if ($currentExpire->isPast()) {
                $owner->expire_date = $now->addDays($daysToAdd)->format('Y-m-d');
            } else {
                $owner->expire_date = $currentExpire->addDays($daysToAdd)->format('Y-m-d');
            }
            $owner->save();

            $owner->save();

            // 🔴 Owner ට Notification එකක් යවනවා 🔴
            SystemNotification::create([
                'user_id' => $owner->id,
                'target_role' => 'owner',
                'title' => 'Subscription Approved! 🎉',
                'message' => 'Your subscription request has been approved and your plan is updated.'
            ]);
        
        }

        return response()->json(['status' => 'success', 'message' => 'Subscription approved! Gym plan updated.']);
    }

    // 5. Super Admin ට - Request එකක් Reject කරන්න
    public function rejectRequest($id)
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') return response()->json(['message' => 'Unauthorized Access.'], 403);

        $request = SubscriptionRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();

        $request->status = 'rejected';
        $request->save();

        // 🔴 Owner ට Notification එකක් යවනවා 🔴
        $owner = \App\Models\User::where('gym_id', $request->gym_id)->whereIn('role', ['owner', 'gym_owner', 'admin'])->first();
        if($owner) {
            SystemNotification::create([
                'user_id' => $owner->id,
                'target_role' => 'owner',
                'title' => 'Subscription Rejected ❌',
                'message' => 'Your recent subscription request was rejected. Please contact support.'
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Subscription request rejected.']);

        return response()->json(['status' => 'success', 'message' => 'Subscription request rejected.']);
    }
}