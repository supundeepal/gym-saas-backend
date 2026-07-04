<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    // අලුත් සාමාජිකයෙක් ලියාපදිංචි කිරීම
    public function store(Request $request)
    {
        $admin = Auth::user();

        // 1. එවන දත්ත නිවැරදිදැයි පරීක්ෂා කිරීම (ඊමේල් එක කලින් පාවිච්චි කරලා නැති වෙන්න ඕන)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // 2. අලුත් සාමාජිකයාව (Member) Database එකට ඇතුළත් කිරීම
        $member = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // පාස්වර්ඩ් එක ආරක්ෂිතව Hash කරනවා
            'role' => 'member', // තනතුර member ලෙස සකසනවා
            'gym_id' => $admin->gym_id, // ඇඩ්මින්ගේ ජිම් එකටම අනුයුක්ත කරනවා
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Member registered successfully',
            'data' => $member
        ], 201);
    }// ජිම් එකේ ඉන්න සියලුම සාමාජිකයින්ගේ ලැයිස්තුව ලබාදීම
    public function index()
    {
        $admin = Auth::user();

        // ඇඩ්මින්ගේ ජිම් එකට අදාළව, 'member' කියන තනතුර (role) තියෙන අයව පමණක් සොයාගැනීම
        $members = User::where('gym_id', $admin->gym_id)
                       ->where('role', 'member')
                       ->get();

        return response()->json([
            'status' => 'success',
            'data' => $members
        ], 200);
    }// සාමාජිකයෙකුට RFID කාඩ්පතක් සම්බන්ධ කිරීම
    public function assignRfid(Request $request)
    {
        $admin = Auth::user();

        // 1. එවන දත්ත පරීක්ෂා කිරීම (member_id අනිවාර්යයි, rfid_number එක වෙනස් කෙනෙකුට දීලා නැතිවෙන්න ඕන)
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'rfid_number' => 'required|string|unique:users,rfid_number'
        ]);

        // 2. අදාළ සාමාජිකයාව සොයාගැනීම (ඇඩ්මින්ගේ ජිම් එකේ පමණක්)
        $member = User::where('id', $request->member_id)
                      ->where('gym_id', $admin->gym_id)
                      ->where('role', 'member')
                      ->first();

        if (!$member) {
            return response()->json([
                'status' => 'error',
                'message' => 'Member not found in your gym'
            ], 404);
        }

        // 3. RFID අංකය යාවත්කාලීන කිරීම
        $member->rfid_number = $request->rfid_number;
        $member->save();

        return response()->json([
            'status' => 'success',
            'message' => 'RFID assigned successfully',
            'data' => $member
        ], 200);
    }
}