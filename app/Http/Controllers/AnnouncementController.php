<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    // Admin ට පමණක් අලුත් දැන්වීමක් දැමීමට
    public function store(Request $request)
    {
        $user = Auth::user();

        // ඇඩ්මින් කෙනෙක්ද කියා පරීක්ෂා කිරීම
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only admins can post announcements'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $announcement = Announcement::create([
            'gym_id' => $user->gym_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement posted successfully',
            'data' => $announcement
        ], 201);
    }

    // ජිම් එකේ සියලුම සාමාජිකයින්ට දැන්වීම් බැලීමට
    public function index()
    {
        $user = Auth::user();

        // අදාළ ජිම් එකේ දැන්වීම් අලුත්ම එක උඩින් එන ලෙස ලබාගැනීම
        $announcements = Announcement::where('gym_id', $user->gym_id)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $announcements
        ], 200);
    }
}