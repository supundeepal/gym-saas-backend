<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // පැමිණීම සටහන් කිරීම (Check-in සහ Check-out)
    public function markAttendance(Request $request)
    {
        $admin = Auth::user();

        
        // ---------------------------------------------

        // Admin කෙනෙක්ද කියා පරීක්ෂා කිරීම
        if ($admin->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only admins can mark attendance'
            ], 403);
        }

        // RFID අංකය හෝ User ID එක අනිවාර්යයෙන්ම තිබිය යුතුයි
        $request->validate([
            'rfid_number' => 'nullable|string',
            'user_id' => 'nullable|integer'
        ]);

        if (!$request->rfid_number && !$request->user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please provide either RFID number or User ID'
            ], 400);
        }

        // සාමාජිකයාව සොයාගැනීම (RFID හෝ ID එක හරහා)
        if ($request->rfid_number) {
            $member = User::where('rfid_number', $request->rfid_number)->first();
        } else {
            $member = User::find($request->user_id);
        }

        // සාමාජිකයාව හමු වුණේ නැත්නම්
        if (!$member) {
            return response()->json([
                'status' => 'error',
                'message' => 'Member not found. Invalid Card or ID.'
            ], 404);
        }

        // අද දිනය සහ මේ වෙලාව ලබාගැනීම
        $today = Carbon::today()->toDateString();
       $now = Carbon::now()->toDateTimeString();

        // අද දවසට අදාළව මේ සාමාජිකයාගේ පැමිණීමක් දැනටමත් තියෙනවද බැලීම
        $attendance = Attendance::where('user_id', $member->id)
                                ->where('date', $today)
                                ->first();

        // පැමිණීමක් නැත්නම් අලුතින් Check-in කිරීම
        if (!$attendance) {
            Attendance::create([
                'user_id' => $member->id,
                'gym_id' => $admin->gym_id,
                'date' => $today,
                'check_in_time' => $now,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-In successful',
                'member_name' => $member->name,
                'time' => $now
            ], 200);
        } 
        // පැමිණීමක් තියෙනවා නම් Check-out කිරීම
        else {
            // දැනටමත් Check-out වෙලා නම්
            if ($attendance->check_out_time) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member has already checked out for today'
                ], 400);
            }

            // Check-out වෙලාව සටහන් කිරීම
            $attendance->update([
                'check_out_time' => $now
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-Out successful',
                'member_name' => $member->name,
                'time' => $now
            ], 200);
        }
    }
}