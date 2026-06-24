<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkoutPlan;
use Illuminate\Support\Facades\Auth;

class WorkoutPlanController extends Controller
{
    // අලුත් Workout Plan එකක් සාමාජිකයෙකුට ලබාදීම
    public function store(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'member_id' => 'required|exists:users,id',
            'plan_name' => 'required|string',
            'description' => 'required|string',
        ]);

        $plan = WorkoutPlan::create([
            'gym_id' => $admin->gym_id,
            'member_id' => $request->member_id,
            'plan_name' => $request->plan_name,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Workout plan assigned successfully',
            'data' => $plan
        ], 201);
    }// සාමාජිකයාට තමන්ගේ Workout Plan එක බලාගැනීම සඳහා
    public function show()
    {
        $member = Auth::user();

        // සාමාජිකයාගේ ID එකට අදාළ ප්ලෑන් එක ලබා ගැනීම
        $plan = WorkoutPlan::where('member_id', $member->id)->first();

        if (!$plan) {
            return response()->json([
                'status' => 'error',
                'message' => 'No workout plan found for you.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $plan
        ], 200);
    }
}