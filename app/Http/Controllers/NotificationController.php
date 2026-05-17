<?php

namespace App\Http\Controllers;

use App\Models\ForumNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = ForumNotification::where('user_id', auth()->id())
            ->with('actor')
            ->latest()
            ->paginate(20);

        ForumNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        cache()->forget("user_notif_count_" . auth()->id());

        return view('notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        ForumNotification::where('id', $id)->where('user_id', auth()->id())
            ->update(['read_at' => now()]);
        cache()->forget("user_notif_count_" . auth()->id());
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        ForumNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        cache()->forget("user_notif_count_" . auth()->id());
        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }
}
