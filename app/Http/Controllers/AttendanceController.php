<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // සාමාජිකයෙකුගේ පැමිණීම (Check-in) සටහන් කිරීම
    public function checkIn(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'member_id' => 'required|exists:users,id'
        ]);

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

        // --- අලුතින් එකතු කළ කොටස: කල් ඉකුත් වීමේ දිනය පරීක්ෂා කිරීම ---
        $today = Carbon::today();
        
        // සාමාජිකයා කිසිදිනෙක මුදල් ගෙවා නැත්නම් හෝ කාලය අවසන් වී ඇත්නම්
        if (!$member->expire_date || Carbon::parse($member->expire_date)->isBefore($today)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Membership Expired. Please make a payment to enter.'
            ], 403);
        }
        // --------------------------------------------------------

        // සියල්ල නිවැරදි නම් පමණක් පැමිණීම Database එකේ සටහන් කිරීම
        $attendance = Attendance::create([
            'gym_id' => $admin->gym_id,
            'user_id' => $member->id,
            'check_in_time' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in successful',
            'data' => $attendance
        ], 201);
    }

    // ජිම් එකේ පැමිණීම් ලැයිස්තුව (Attendance List) ලබාදීම
    public function index()
    {
        $admin = Auth::user();

        $attendances = Attendance::where('gym_id', $admin->gym_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $attendances
        ], 200);
    }
}