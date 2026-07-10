<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkoutPlanController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;

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
    
    // පැමිණීම් (Attendance) සඳහා
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance']);

    Route::post('/members/assign-rfid', [MemberController::class, 'assignRfid']);

    Route::post('/register-gym', [AuthController::class, 'registerGym']);

    // Dashboard දත්ත ගන්න රූට් එක
    Route::get('/dashboard-stats', [AuthController::class, 'getDashboardStats']);

    // සියලුම ජිම් විස්තර ගන්න රූට් එක
    Route::get('/all-gyms', [AuthController::class, 'getAllGyms']);

// Edit සහ Delete රූට්ස්
    Route::put('/gyms/{id}', [AuthController::class, 'updateGym']);
    Route::delete('/gyms/{id}', [AuthController::class, 'deleteGym']);

// Gym Owners ලව ගන්න රූට් එක
    Route::get('/gym-owners', [AuthController::class, 'getAllGymOwners']);

    Route::get('/plans', [AuthController::class, 'getAllPlans']);

    Route::post('/plans', [AuthController::class, 'storePlan']);
    Route::delete('/plans/{id}', [AuthController::class, 'deletePlan']);

    Route::get('/sms-packages', [AuthController::class, 'getSmsPackages']);
    Route::post('/sms-packages', [AuthController::class, 'storeSmsPackage']);
    Route::delete('/sms-packages/{id}', [AuthController::class, 'deleteSmsPackage']);

    Route::get('/owner/dashboard-stats', [AuthController::class, 'getOwnerDashboardStats']);

    Route::post('/admin/impersonate/{id}', [AuthController::class, 'impersonateOwner']);

    // Gym Members ලාව Add කිරීම සඳහා
    Route::post('/owner/members', [\App\Http\Controllers\MemberController::class, 'store']);

    // Members ලා සම්බන්ධ රූට්ස් (auth:sanctum ඇතුළේ දාන්න)
    Route::get('/owner/members', [\App\Http\Controllers\MemberController::class, 'index']);
    Route::post('/owner/members', [\App\Http\Controllers\MemberController::class, 'store']);
    Route::post('/owner/members/rfid', [\App\Http\Controllers\MemberController::class, 'assignRfid']);

    Route::get('/packages', [App\Http\Controllers\PackageController::class, 'index']);
Route::post('/packages', [App\Http\Controllers\PackageController::class, 'store']);

Route::put('/packages/{id}', [App\Http\Controllers\PackageController::class, 'update']);
Route::delete('/packages/{id}', [App\Http\Controllers\PackageController::class, 'destroy']);

// Attendance සඳහා API Routes
Route::get('/attendance/today', [App\Http\Controllers\AttendanceController::class, 'today']);
Route::post('/attendance/mark', [App\Http\Controllers\AttendanceController::class, 'mark']);
Route::delete('/attendance/{id}', [App\Http\Controllers\AttendanceController::class, 'destroy']);

// ==========================================
// SMS Packages කළමනාකරණය (Super Admin සහ Owner දෙගොල්ලොන්ටම)
// ==========================================
Route::get('/sms-packages', [App\Http\Controllers\SmsPackageController::class, 'index']); // පැකේජ් බලන්න
Route::post('/admin/sms-packages', [App\Http\Controllers\SmsPackageController::class, 'store']); // අලුත් පැකේජ් හදන්න
Route::delete('/admin/sms-packages/{id}', [App\Http\Controllers\SmsPackageController::class, 'destroy']); // මකන්න

// Gym Owner SMS Routes
Route::get('/owner/sms-balance', [App\Http\Controllers\SmsController::class, 'getBalance']);
Route::post('/owner/sms/send', [App\Http\Controllers\SmsController::class, 'sendSms']);
Route::post('/owner/sms/buy', [App\Http\Controllers\SmsController::class, 'buyPackage']); // මේකෙන් තමයි ස්ලිප් එක යවන්නේ

}); // ආරක්ෂිත කලාපය අවසානය
    
