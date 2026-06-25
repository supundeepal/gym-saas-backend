<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkoutPlanController;
use App\Http\Controllers\AnnouncementController;

// ලොග් වෙන්න තියෙන Route එක 
Route::post('/login', [AuthController::class, 'login']);


// ආරක්ෂිත කලාපය ආරම්භය (මෙතනින් ඇතුළට යන්න අනිවාර්යයෙන්ම Token එක ඕන)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/packages', [PackageController::class, 'index']);
    Route::post('/packages', [PackageController::class, 'store']);

    // අලුත් සාමාජිකයෙක් ලියාපදිංචි කරන Route එක
    Route::post('/members', [MemberController::class, 'store']);

    // ජිම් එකේ සාමාජිකයින් ලැයිස්තුව බලන Route එක (GET request)
    Route::get('/members', [MemberController::class, 'index']);
    // සාමාජිකයෙකුගේ පැමිණීම සටහන් කරන Route එක
Route::post('/check-in', [AttendanceController::class, 'checkIn']);
// පැමිණීම් ලැයිස්තුව බලන Route එක (GET request)
    Route::get('/attendances', [AttendanceController::class, 'index']);
    // ගෙවීමක් සටහන් කරන Route එක
Route::post('/payments', [PaymentController::class, 'store']);
// ගෙවීම් ලැයිස්තුව බලන Route එක (GET request)
    Route::get('/payments', [PaymentController::class, 'index']);
    // Dashboard දත්ත බලන Route එක
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::post('/workout-plans', [WorkoutPlanController::class, 'store']);
Route::get('/my-workout-plan', [WorkoutPlanController::class, 'show']);
// දැන්වීම් (Announcements) සඳහා Routes
Route::post('/announcements', [AnnouncementController::class, 'store']); // දැන්වීම් දැමීමට
Route::get('/announcements', [AnnouncementController::class, 'index']);  // දැන්වීම් බැලීමට

}); // ආරක්ෂිත කලාපය අවසානය