<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 1. Attendance වෙබ් පිටුව පෙන්වන function එක
    public function viewAttendance()
    {
        return view('owner.attendance');
    }

    // 2. අද දවසේ පැමිණි අයගේ ලැයිස්තුව යවන function එක
    public function today()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        $attendances = Attendance::where('attendances.gym_id', $user->gym_id)
            ->where('attendances.date', $today)
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->select('attendances.*', 'users.name as member_name', 'users.rfid_number')
            ->orderBy('attendances.check_in_time', 'desc')
            ->get();

        $attendances->transform(function($item) {
            $item->method = $item->rfid_number ? 'RFID' : 'Manual';
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $attendances
        ]);
    }

    // 3. පැමිණීම සටහන් කිරීම (Check-in සහ Check-out - ඔයාගේ පරණ ලොජික් එක)
    public function mark(Request $request)
    {
        $admin = Auth::user();
        $identifier = $request->identifier; // Frontend එකෙන් එවන දත්තය (RFID, Email, හෝ Phone)

        // සාමාජිකයාව සොයාගැනීම
        $member = User::where('gym_id', $admin->gym_id)
            ->where(function($query) use ($identifier) {
                $query->where('rfid_number', $identifier)
                      ->orWhere('phone', $identifier)
                      ->orWhere('email', $identifier);
            })->first();

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
                'message' => 'Check-In successful for ' . $member->name
            ], 200);
        } 
        // පැමිණීමක් තියෙනවා නම් Check-out කිරීම
        else {
            if ($attendance->check_out_time) {
                return response()->json([
                    'status' => 'error',
                    'message' => $member->name . ' has already checked out for today.'
                ], 400);
            }

            $attendance->update([
                'check_out_time' => $now
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-Out successful for ' . $member->name
            ], 200);
        }
    }

    // 4. වැරදිලා මාර්ක් වුනොත් ඒක මකන function එක
    public function destroy($id)
    {
        $user = Auth::user();
        $attendance = Attendance::where('gym_id', $user->gym_id)->find($id);
        
        if ($attendance) {
            $attendance->delete();
            return response()->json(['status' => 'success', 'message' => 'Attendance removed.']);
        }
        
        return response()->json(['status' => 'error', 'message' => 'Record not found.'], 404);
    }
}