<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym;
use App\Models\User;
use App\Models\SmsPackage;
use App\Models\SmsPurchase;
use Illuminate\Support\Facades\Auth;


class SmsController extends Controller
{
    // 1. Gym Owner ගේ දැනට තියෙන SMS Balance එක ගැනීම
    public function getBalance()
    {
        $user = Auth::user();
        $gym = Gym::find($user->gym_id);

        return response()->json([
            'status' => 'success',
            'balance' => $gym ? $gym->sms_balance : 0
        ]);
    }

    // 2. Gym Owner SMS Package එකක් මිලදී ගැනීමට Slip එක Upload කිරීම
    public function buyPackage(Request $request)
    {
        // Slip එක පින්තූරයක්ද කියලා චෙක් කරනවා
        $request->validate([
            'package_id' => 'required|integer',
            'slip' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();
        
        $package = SmsPackage::find($request->package_id);
        if (!$package) {
            return response()->json(['status' => 'error', 'message' => 'Package not found.']);
        }

        // Slip පින්තූරය public/storage/slips කියන ෆෝල්ඩර් එකට සේව් කරනවා
        $slipPath = $request->file('slip')->store('slips', 'public');

        // Pending රික්වෙස්ට් එකක් විදිහට ඩේටාබේස් එකට දානවා
        SmsPurchase::create([
            'gym_id' => $user->gym_id,
            'sms_package_id' => $package->id,
            'slip_path' => $slipPath,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Your payment slip has been uploaded successfully. The package will be activated once the admin verifies the payment.'
        ]);
    }

    // 3. SMS යැවීම
    public function sendSms(Request $request)
    {
        $user = Auth::user();
        $gym = Gym::find($user->gym_id);
        
        $request->validate([
            'target' => 'required|string',
            'message' => 'required|string'
        ]);

        $messageLength = strlen($request->message);
        $smsCountPerPerson = ceil($messageLength / 160) ?: 1;

        $memberCount = User::where('gym_id', $user->gym_id)->where('role', 'member')->count();

        if ($memberCount === 0) {
            return response()->json(['status' => 'error', 'message' => 'No members found to send SMS.']);
        }

        $totalSmsNeeded = $memberCount * $smsCountPerPerson;

        if ($gym->sms_balance < $totalSmsNeeded) {
            return response()->json([
                'status' => 'error', 
                'message' => "Insufficient balance. You need {$totalSmsNeeded} SMS but have only {$gym->sms_balance}."
            ]);
        }

        // Balance එකෙන් අඩු කිරීම
        $gym->sms_balance -= $totalSmsNeeded;
        $gym->save();

        // (අඩු වෙනකොට 20% / 5% ද කියලා බලලා Email/SMS යවන කොටස අපි ඊළඟට මෙතනට දාමු)

        return response()->json([
            'status' => 'success',
            'message' => "Successfully sent {$totalSmsNeeded} SMS to {$memberCount} members!"
        ]);
    }// Gym Owner ගේ SMS මිලදීගැනීම් ඉතිහාසය බැලීම
    public function getMyPurchases()
    {
        $user = Auth::user();
        $purchases = SmsPurchase::with('package')
                        ->where('gym_id', $user->gym_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return response()->json(['status' => 'success', 'data' => $purchases]);
    }
}