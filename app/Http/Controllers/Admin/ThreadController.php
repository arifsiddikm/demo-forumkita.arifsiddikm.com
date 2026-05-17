<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Report;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        $threads = Thread::with(['user', 'category'])
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->latest()
            ->paginate(20);

        return view('admin.threads.index', compact('threads'));
    }

    public function pin(Thread $thread)
    {
        $thread->update(['is_pinned' => !$thread->is_pinned]);
        return back()->with('success', 'Status pin thread diperbarui.');
    }

    public function hot(Thread $thread)
    {
        $thread->update(['is_hot' => !$thread->is_hot]);
        return back()->with('success', 'Status hot thread diperbarui.');
    }

    public function lock(Thread $thread)
    {
        $thread->update(['is_locked' => !$thread->is_locked]);
        return back()->with('success', 'Status lock thread diperbarui.');
    }

    public function announce(Thread $thread)
    {
        $thread->update(['is_announcement' => !$thread->is_announcement]);
        return back()->with('success', 'Status announcement thread diperbarui.');
    }

    public function destroy(Thread $thread)
    {
        $thread->delete();
        return redirect()->route('admin.threads.index')->with('success', 'Thread berhasil dihapus.');
    }
}
