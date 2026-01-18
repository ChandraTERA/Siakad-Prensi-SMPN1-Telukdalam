<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = Notification::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::forUser($user->id)->unread()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::forUser($user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread notifications count for AJAX requests.
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $unreadCount = Notification::forUser($user->id)->unread()->count();

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Get recent notifications for dropdown/notification bell.
     */
    public function getRecent()
    {
        $user = Auth::user();
        
        $notifications = Notification::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::forUser($user->id)->unread()->count()
        ]);
    }

    /**
     * Display notifications for Admin role.
     */
    public function adminIndex()
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            abort(403);
        }
        
        $notifications = Notification::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::forUser($user->id)->unread()->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Display notifications for Siswa role.
     */
    public function siswaIndex()
    {
        $user = Auth::user();
        
        if ($user->role !== 'siswa') {
            abort(403);
        }
        
        $notifications = Notification::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::forUser($user->id)->unread()->count();

        return view('siswa.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }
}