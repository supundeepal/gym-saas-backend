<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Dashboard එකට අවශ්‍ය දත්ත ලබා දීම
    public function index()
    {
        $admin = Auth::user();
        $today = Carbon::today(); // අද දිනය ලබා ගැනීම

        // 1. ජිම් එකේ ඉන්න සම්පූර්ණ සාමාජිකයින් ගණන (Total Members)
        $totalMembers = User::where('gym_id', $admin->gym_id)
                            ->where('role', 'member')
                            ->count();

        // 2. අද දවසේ පැමිණීම් ගණන (Today Check-ins)
        $todayAttendances = Attendance::where('gym_id', $admin->gym_id)
                                      ->whereDate('created_at', $today)
                                      ->count();

        // 3. අද දවසේ එකතු වුණු සම්පූර්ණ මුදල (Today Revenue)
        $todayRevenue = Payment::where('gym_id', $admin->gym_id)
                               ->whereDate('created_at', $today)
                               ->sum('amount');

        // දත්ත ටික එකට එකතු කරලා යැවීම
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_members' => $totalMembers,
                'today_attendances' => $todayAttendances,
                'today_revenue' => $todayRevenue
            ]
        ], 200);
    }
}