<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Gym; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // 1. යූසර් කෙනෙක් ලොග් වෙන function එක
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

       if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            $token = $user->createToken('GymSaaSToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged in',
                'token' => $token,
                'user' => $user
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password'
        ], 401);
    }

    // 2. Super Admin විසින් අලුත් ජිම් එකක් සහ එහි අයිතිකරු ලියාපදිංචි කිරීම
    public function registerGym(Request $request)
    {
        $superAdmin = Auth::user();

        // මේක කරන්න පුළුවන් Super Admin ට විතරක් බව තහවුරු කිරීම
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access. Only Super Admins can do this.'], 403);
        }

        $request->validate([
            'gym_name' => 'required|string',
            'owner_name' => 'required|string',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|string|min:6',
        ]);

        // අලුත් ජිම් එක Database එකට දැමීම (Slug එකත් එක්ක නිවැරදිව)
        $gym = Gym::create([
            'name' => $request->gym_name,
            'slug' => Str::slug($request->gym_name), 
        ]);

        // ජිම් එකේ අයිතිකරුව (Admin) Database එකට දැමීම
        $owner = User::create([
            'name' => $request->owner_name,
            'email' => $request->owner_email,
            'password' => Hash::make($request->owner_password),
            'role' => 'admin',
            'gym_id' => $gym->id, 
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Gym and Gym Owner created successfully',
            'gym' => $gym,
            'owner' => $owner
        ], 201);
    } // 👉 අර කලින් අඩුවෙලා තිබුණු වරහන මෙන්න මේකයි! 👈

    // 3. Super Admin ගේ ඩෑෂ්බෝඩ් එකට දත්ත යැවීම
    public function getDashboardStats()
    {
        $superAdmin = Auth::user();

        // Super Admin ට විතරක් දත්ත දෙනවා
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        // Database එකෙන් දත්ත ගැනීම
        $totalGyms = Gym::count();
        $totalOwners = User::where('role', 'admin')->count();
        
        // අලුතින්ම රෙජිස්ටර් කරපු ජිම් 5 ක ලිස්ට් එකක් (අයිතිකාරයත් එක්ක)
        $recentGyms = Gym::orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'status' => 'success',
            'stats' => [
                'total_gyms' => $totalGyms,
                'active_owners' => $totalOwners,
                'total_members' => 0, // මේක පස්සේ හදමු Members ලා එකතු කරද්දී
                'revenue' => 'Rs. 0'  // මේකත් Payment දාද්දී හදමු
            ],
            'recent_gyms' => $recentGyms
        ], 200);
    
    }// 4. Manage Gyms පිටුවට සියලුම ජිම් වල විස්තර යැවීම
    public function getAllGyms()
    {
        $superAdmin = Auth::user();

        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        $gyms = \Illuminate\Support\Facades\DB::table('gyms')
            ->leftJoin('users', 'gyms.id', '=', 'users.gym_id')
            // 👇 මෙන්න මෙතනට gyms.sms_balance එක එකතු කළා 👇
            ->select('gyms.id', 'gyms.name as gym_name', 'gyms.created_at', 'gyms.sms_balance', 'users.name as owner_name', 'users.email as owner_email')
            ->orderBy('gyms.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'gyms' => $gyms
        ], 200);
    
    }// 5. ජිම් එකක විස්තර Update කිරීම (Edit)
    public function updateGym(Request $request, $id)
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        $request->validate([
            'gym_name' => 'required|string',
            'owner_name' => 'required|string'
        ]);

        $gym = Gym::find($id);
        if (!$gym) return response()->json(['message' => 'Gym not found'], 404);

        // ජිම් එකේ නම අප්ඩේට් කරනවා
        $gym->name = $request->gym_name;
        $gym->slug = Str::slug($request->gym_name);
        $gym->save();

        // අයිතිකාරයාගේ නමත් අප්ඩේට් කරනවා
        $owner = User::where('gym_id', $id)->where('role', 'admin')->first();
        if ($owner) {
            $owner->name = $request->owner_name;
            $owner->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Gym updated successfully!']);
    }

    // 6. ජිම් එකක් සිස්ටම් එකෙන් මකා දැමීම (Delete)
    public function deleteGym($id)
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        $gym = Gym::find($id);
        if (!$gym) return response()->json(['message' => 'Gym not found'], 404);

        // ජිම් එක මකන්න කලින්, ඒකට සම්බන්ධ යූසර්ලා (Owners) ටික මකන්න ඕන
        User::where('gym_id', $id)->delete();
        
        // ඊට පස්සේ ජිම් එක මකනවා
        $gym->delete();

        return response()->json(['status' => 'success', 'message' => 'Gym and its owner deleted successfully!']);
    } // 7. Gym Owners ලගේ ලිස්ට් එක යැවීම
    public function getAllGymOwners()
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        // 'admin' (Gym Owner) රෝල් එක තියෙන යූසර්ලා විතරක් ගන්නවා, ඒකට අදාළ ජිම් එකේ නමත් එක්ක
        $owners = \Illuminate\Support\Facades\DB::table('users')
            ->where('users.role', 'admin')
            ->leftJoin('gyms', 'users.gym_id', '=', 'gyms.id')
            ->select('users.id', 'users.name as owner_name', 'users.email', 'users.created_at', 'gyms.name as gym_name')
            ->orderBy('users.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'owners' => $owners
        ], 200);
    }// 8. සිස්ටම් එකේ තියෙන Plans සියල්ලම යැවීම
    public function getAllPlans()
    {
        // Plans ටේබල් එකෙන් ඔක්කොම විස්තර ගන්නවා
        $plans = \App\Models\Plan::all();

        return response()->json([
            'status' => 'success',
            'plans' => $plans
        ], 200);
    }// 9. අලුත් SaaS Plan එකක් ඇතුළත් කිරීම (Add New Plan)
    public function storePlan(Request $request)
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') return response()->json(['message' => 'Unauthorized Access.'], 403);

        $featuresArray = explode(',', $request->features); // කොමා වලින් වෙන් කරපු Features ටික Array එකක් කරනවා

        $plan = \App\Models\Plan::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'price' => $request->price,
            'max_members' => $request->max_members,
            'features' => $featuresArray,
            'is_active' => true
        ]);

        return response()->json(['status' => 'success', 'message' => 'Plan created successfully!']);
    }

    // 10. SaaS Plan එකක් Delete කිරීම
    public function deletePlan($id)
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') return response()->json(['message' => 'Unauthorized Access.'], 403);

        \App\Models\Plan::where('id', $id)->delete();
        return response()->json(['status' => 'success']);
    }// SMS Packages එළියට ගැනීම
    public function getSmsPackages()
    {
        $packages = \App\Models\SmsPackage::all();
        return response()->json(['status' => 'success', 'packages' => $packages]);
    }

    // අලුත් SMS Package එකක් සේව් කිරීම
    public function storeSmsPackage(Request $request)
    {
        \App\Models\SmsPackage::create($request->all());
        return response()->json(['status' => 'success']);
    }

    // SMS Package එකක් Delete කිරීම
    public function deleteSmsPackage($id)
    {
        \App\Models\SmsPackage::where('id', $id)->delete();
        return response()->json(['status' => 'success']);
    }
}