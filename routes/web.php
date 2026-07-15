<?php

use Illuminate\Support\Facades\Route;

// ඩෑෂ්බෝඩ් එක (ප්‍රධාන පිටුව)
Route::get('/', function () {
    return view('dashboard');
});

// 1. Super Admin Login
Route::get('/admin/login', function () {
    return view('auth.admin-login');
});

// කවුරුහරි වැරදිලා /login කියලා ගැහුවොත්, කෙලින්ම admin-login එකට යවන්න (404 Error එක නැති කරන්න)
Route::redirect('/login', '/admin/login');

// 2. Gym Owner & Staff Login Portal
Route::get('/portal/login', function () {
    return view('auth.portal-login');
})->name('login'); 

// ================= Super Admin ගේ පිටු =================
Route::get('/manage-gyms', function () {
    return view('manage-gyms');
});

Route::get('/gym-owners', function () {
    return view('gym-owners');
});

Route::get('/subscriptions', function () {
    return view('subscriptions');
});

// (Optional) අර පරණ /admin/sms-packages ලින්ක් එකට ගියොත්, කෙලින්ම Subscriptions පිටුවටම යවන්න
Route::get('/admin/sms-packages', function () {
    return redirect('/subscriptions');
});


// ================= Gym Owner ට අදාළ පිටු =================
Route::get('/owner-dashboard', function () {
    return view('owner.dashboard'); 
});

Route::get('/owner/members', function () {
    return view('owner.members'); 
});

Route::get('/owner/memberships', [App\Http\Controllers\PackageController::class, 'viewMemberships']);

Route::get('/owner/attendance', [App\Http\Controllers\AttendanceController::class, 'viewAttendance']);

Route::get('/owner/sms', function () {
    return view('owner.sms');
});

Route::get('/owner/subscription', function () {
    return view('owner.subscription');
});