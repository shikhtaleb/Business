<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(string $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['status' => 'ok']);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'تم تعليم جميع الإشعارات كمقروءة.');
    }

    public function destroy(string $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'تم حذف الإشعار.');
    }
}
