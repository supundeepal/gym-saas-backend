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
    }
}