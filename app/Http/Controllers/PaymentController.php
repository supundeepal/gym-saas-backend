<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // දිනයන් සහ වේලාවන් හැසිරවීමට මෙය අනිවාර්යයෙන්ම අවශ්‍ය වේ

class PaymentController extends Controller
{
    // අලුත් ගෙවීමක් සටහන් කිරීම සහ සාමාජිකත්වය දික් කිරීම
    public function store(Request $request)
    {
        $admin = Auth::user();

        // 1. එවන දත්ත පරීක්ෂා කිරීම (member_id, amount, payment_type අනිවාර්යයි)
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'payment_type' => 'required|string',
        ]);

        // 2. අදාළ සාමාජිකයාව සොයාගැනීම සහ ඔහු මේ ජිම් එකටම අයිතිදැයි තහවුරු කරගැනීම
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

        // 3. ගෙවීම Database එකේ සටහන් කිරීම
        $payment = Payment::create([
            'gym_id' => $admin->gym_id,
            'user_id' => $member->id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
        ]);

        // 4. සාමාජිකත්වය අද සිට දින 30කින් දික් කිරීම
        $member->expire_date = Carbon::now()->addDays(30);
        $member->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment recorded and membership extended by 30 days',
            'data' => [
                'payment' => $payment,
                'new_expire_date' => $member->expire_date->format('Y-m-d') // අලුත් දිනය පෙන්වීම
            ]
        ], 201);
    }

    // ජිම් එකට ලැබුණු සියලුම ගෙවීම් ලැයිස්තුව ලබාදීම
    public function index()
    {
        $admin = Auth::user();

        // ඇඩ්මින්ගේ ජිම් එකට අදාළ සියලුම ගෙවීම් සටහන් ලබා ගැනීම
        $payments = Payment::where('gym_id', $admin->gym_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $payments
        ], 200);
    }
}