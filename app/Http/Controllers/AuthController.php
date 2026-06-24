<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // යූසර් කෙනෙක් ලොග් වෙන function එක
    public function login(Request $request)
    {
        // 1. එවන ඊමේල් එක සහ පාස්වර්ඩ් එක හරියටම තියෙනවද කියලා පරීක්ෂා කරනවා
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Database එකේ දත්ත එක්ක ගළපලා ලොග් වෙන්න උත්සාහ කරනවා
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            
            // 3. ලොග් වුණාට පස්සේ ආරක්ෂිත Token එකක් (ඩිජිටල් යතුරක්) හදනවා
            $token = $user->createToken('GymSaaSToken')->plainTextToken;

            // 4. ඇප් එකට ඒ Token එක සහ යූසර්ගේ විස්තර යවනවා
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged in',
                'token' => $token,
                'user' => $user
            ], 200);
        }

        // 5. ඊමේල් එක හරි පාස්වර්ඩ් එක හරි වැරදි නම් මේක යවනවා
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password'
        ], 401);
    }
}