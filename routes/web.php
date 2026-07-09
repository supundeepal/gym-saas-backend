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

// 2. Gym Owner & Staff Login Portal
Route::get('/portal/login', function () {
    return view('auth.portal-login');
})->name('login'); // 👈 මෙන්න මේ කෑල්ල තමයි අලුතින් එකතු වෙන්න ඕන!

// Manage Gyms පිටුව
Route::get('/manage-gyms', function () {
    return view('manage-gyms');
});

// Gym Owners පිටුව
Route::get('/gym-owners', function () {
    return view('gym-owners');
});

Route::get('/subscriptions', function () {
    return view('subscriptions');
});
Route::get('/owner-dashboard', function () {
    return view('owner.dashboard'); 
});

// Gym Owner ට අදාළ පිටු
Route::get('/owner-dashboard', function () {
    return view('owner.dashboard');
});

// 👈 මේ අලුත් පේළිය එකතු කරන්න
Route::get('/owner/members', function () {
    return view('owner.members'); 
});