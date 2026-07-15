<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemNotification;

class NotificationController extends Controller
{
    // නොකියවපු මැසේජ් ටිකයි, ගාණයි (Count) ගන්න Function එක
    public function getNotifications()
    {
        $user = Auth::user();
        
        if ($user->role === 'super_admin') {
            // Super Admin ට අදාළ මැසේජ්
            $notifications = SystemNotification::where('target_role', 'super_admin')
                                ->orderBy('created_at', 'desc')->take(10)->get();
            $unread = SystemNotification::where('target_role', 'super_admin')->where('is_read', false)->count();
        } else {
            // Gym Owner ට අදාළ මැසේජ්
            $notifications = SystemNotification::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')->take(10)->get();
            $unread = SystemNotification::where('user_id', $user->id)->where('is_read', false)->count();
        }

        return response()->json([
            'status' => 'success', 
            'notifications' => $notifications, 
            'unread_count' => $unread
        ]);
    }

    // මැසේජ් ඔක්කොම කියෙව්වා (Mark as Read) කරන්න
    public function markAsRead()
    {
        $user = Auth::user();
        if ($user->role === 'super_admin') {
            SystemNotification::where('target_role', 'super_admin')->update(['is_read' => true]);
        } else {
            SystemNotification::where('user_id', $user->id)->update(['is_read' => true]);
        }
        return response()->json(['status' => 'success']);
    }
}