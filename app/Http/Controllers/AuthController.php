<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Gym; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    // 2. අලුත් Gym එකක් සහ Owner කෙනෙක් රෙජිස්ටර් කිරීම (Plan එකත් එක්කම)
    public function registerGym(Request $request)
    {
        $request->validate([
            // Gym විස්තර
            'gym_name' => 'required|string|max:255',
            'gym_address' => 'nullable|string',
            'gym_phone' => 'nullable|string',
            'gym_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Owner විස්තර
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|min:6',
            'owner_nic' => 'nullable|string|max:20',
            'owner_phone' => 'nullable|string|max:20',

            // Plan විස්තර
            'plan_id' => 'required' 
        ]);

        // Logo එකක් තියෙනවද බලලා සේව් කරනවා
        $logoPath = null;
        if ($request->hasFile('gym_logo')) {
            $logoPath = $request->file('gym_logo')->store('gym_logos', 'public');
        }

        // Gym එක හදනවා (Slug එකයි Plan ID එකයි එක්කම)
        $gym = Gym::create([
            'name' => $request->gym_name,
            'slug' => Str::slug($request->gym_name), 
            'plan_id' => $request->plan_id, // 👈 මේක අලුතින් දැම්මේ
            'address' => $request->gym_address,
            'phone' => $request->gym_phone,
            'logo_path' => $logoPath,
            'sms_balance' => 0
        ]);

        // තෝරගත්ත Plan එකට අනුව, අද ඉඳන් දින 30 කින් Expire වෙන විදිහට දවස හදනවා
        $expireDate = Carbon::now()->addDays(30)->format('Y-m-d');

        // Owner ව හදනවා
        $user = User::create([
            'name' => $request->owner_name,
            'email' => $request->owner_email,
            'password' => bcrypt($request->owner_password),
            'role' => 'owner',
            'gym_id' => $gym->id,
            'nic' => $request->owner_nic,
            'phone' => $request->owner_phone,
            'expire_date' => $expireDate, 
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Gym and Owner registered successfully with the selected Plan!'
        ]);
    }

    // 🔴 3. අලුතින් එකතු කරපු Function එක: සිස්ටම් එකේ තියෙන ඔක්කොම ජිම් ටික යැවීම 🔴
    public function getAllGyms()
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        // Gyms ටේබල් එකයි, Users (Owner) ටේබල් එකයි සම්බන්ද කරලා ඩේටා ගන්නවා
        $gyms = DB::table('gyms')
            ->leftJoin('users', function($join) {
                $join->on('gyms.id', '=', 'users.gym_id')
                     ->whereIn('users.role', ['owner', 'gym_owner', 'admin']);
            })
            ->select('gyms.*', 'users.name as owner_name', 'users.email as owner_email')
            ->orderBy('gyms.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'gyms' => $gyms
        ]);
    }

    // 🔴 4. Super Admin ගේ ඩෑෂ්බෝඩ් එකේ Stats යවන Function එක 🔴
    public function getDashboardStats()
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        $totalGyms = Gym::count();
        $totalOwners = User::whereIn('role', ['owner', 'gym_owner', 'admin'])->count();
        $totalMembers = User::where('role', 'member')->count();

        // දැනට මෑතකදී රෙජිස්ටර් වුණු ජිම් 5ක් ගන්නවා
        $recentGyms = DB::table('gyms')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'stats' => [
                'total_gyms' => $totalGyms,
                'active_owners' => $totalOwners,
                'total_members' => $totalMembers,
                'monthly_revenue' => 0
            ],
            'recent_gyms' => $recentGyms
        ]);
    }

    // 5. ජිම් එකක විස්තර Update කිරීම (Edit)
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
        $owner = User::where('gym_id', $id)->whereIn('role', ['gym_owner', 'admin', 'owner'])->first();
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
    } 
    
    // 7. Gym Owners ලගේ ලිස්ට් එක යැවීම
    public function getAllGymOwners()
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        $owners = DB::table('users')
            ->whereIn('users.role', ['gym_owner', 'admin', 'owner'])
            ->leftJoin('gyms', 'users.gym_id', '=', 'gyms.id')
            ->select('users.id', 'users.name as owner_name', 'users.email', 'users.created_at', 'gyms.name as gym_name')
            ->orderBy('users.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'owners' => $owners
        ], 200);
    }
    
    // 8. සිස්ටම් එකේ තියෙන Plans සියල්ලම යැවීම
    public function getAllPlans()
    {
        $plans = \App\Models\Plan::all();

        return response()->json([
            'status' => 'success',
            'plans' => $plans
        ], 200);
    }
    
    // 9. අලුත් SaaS Plan එකක් ඇතුළත් කිරීම (Add New Plan)
    public function storePlan(Request $request)
    {
        $superAdmin = Auth::user();
        if ($superAdmin->role !== 'super_admin') return response()->json(['message' => 'Unauthorized Access.'], 403);

        $featuresArray = explode(',', $request->features); 

        $plan = \App\Models\Plan::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
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
    }
    
    // 11. SMS Packages එළියට ගැනීම
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

    // 12. Super Admin ට Gym Owner කෙනෙක් විදිහට ලොග් වීමට (Impersonate)
    public function impersonateOwner($id)
    {
        $superAdmin = Auth::user();
        
        if ($superAdmin->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        $ownerRoles = ['gym_owner', 'admin', 'owner'];
        $targetOwner = User::find($id);

        if (!$targetOwner) {
            $targetOwner = User::where('gym_id', $id)->whereIn('role', $ownerRoles)->first();
        }

        if (!$targetOwner) {
            return response()->json(['message' => 'User account not found.'], 404);
        }

        if (!in_array($targetOwner->role, $ownerRoles, true)) {
            return response()->json(['message' => 'This user is not a Gym Owner. (Current Role: ' . $targetOwner->role . ')'], 404);
        }

        $token = $targetOwner->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 200);
    }

    // 13. Owner ගේ Dashboard එකට Stats යැවීම
    public function getOwnerDashboardStats()
    {
        $user = Auth::user();

        if ($user->role !== 'gym_owner' && $user->role !== 'admin' && $user->role !== 'owner') {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        $gym = Gym::find($user->gym_id);
        $memberCount = User::where('gym_id', $user->gym_id)->where('role', 'member')->count();

        return response()->json([
            'status' => 'success',
            'stats' => [
                'gym_name' => optional($gym)->name,
                'members' => $memberCount,
                'gym_id' => $user->gym_id,
                'owner_name' => $user->name,
                'sms_balance' => optional($gym)->sms_balance ?? 0,
                'monthly_revenue' => 0, 
            ]
        ], 200);
    }
}