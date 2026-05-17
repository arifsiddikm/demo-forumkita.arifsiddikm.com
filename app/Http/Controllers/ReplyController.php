<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    // No __construct — middleware handled at route level (Laravel 11)

    public function store(Request $request, Thread $thread)
    {
        if ($thread->is_locked) {
            return back()->with('error', 'Thread ini dikunci, tidak bisa menambah balasan.');
        }

        $validated = $request->validate([
            'body'           => ['required', 'string', 'min:5'],
            'quoted_user'    => ['nullable', 'string', 'max:50'],
            'quoted_content' => ['nullable', 'string', 'max:500'],
        ]);

        $allowedTags = '<p><br><strong><em><u><s><ul><ol><li><blockquote><a><code><pre><img><figure><figcaption><table><thead><tbody><tr><th><td><h2><h3>';
        $body = strip_tags($validated['body'], $allowedTags);

        Reply::create([
            'thread_id'      => $thread->id,
            'user_id'        => Auth::id(),
            'body'           => $body,
            'quoted_user'    => $validated['quoted_user'] ?? null,
            'quoted_content' => $validated['quoted_content'] ?? null,
        ]);

        return redirect()->route('threads.show', $thread->slug)
            ->with('success', 'Balasan berhasil ditambahkan!')
            ->fragment('replies');
    }

    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:5'],
        ]);

        $allowedTags = '<p><br><strong><em><u><s><ul><ol><li><blockquote><a><code><pre><img>';
        $body = strip_tags($validated['body'], $allowedTags);
        $reply->update(['body' => $body]);

        return redirect()->route('threads.show', $reply->thread->slug)
            ->with('success', 'Balasan berhasil diperbarui!')
            ->fragment("reply-{$reply->id}");
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);
        $thread = $reply->thread;
        $reply->delete();
        return redirect()->route('threads.show', $thread->slug)
            ->with('success', 'Balasan berhasil dihapus.');
    }

    public function like(Reply $reply)
    {
        $user = Auth::user();
        $existing = $reply->likes()->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $reply->decrement('likes_count');
        } else {
            $reply->likes()->create(['user_id' => $user->id]);
            $reply->increment('likes_count');
        }

        return back();
    }

    public function markSolution(Reply $reply)
    {
        $thread = $reply->thread;
        abort_if(Auth::id() !== $thread->user_id && !Auth::user()->is_admin, 403);

        $thread->replies()->where('is_solution', true)->update(['is_solution' => false]);
        $reply->update(['is_solution' => !$reply->is_solution]);

        if ($reply->is_solution) {
            $thread->update(['is_solved' => true]);
            $reply->user->addReputation(20);
        } else {
            $thread->update(['is_solved' => false]);
        }

        return back()->with('success', 'Solusi berhasil ditandai!');
    }

    public function report(Request $request, Reply $reply)
    {
        $request->validate(['reason' => 'required|string|max:255']);
        Report::create([
            'reporter_id'     => auth()->id(),
            'reportable_id'   => $reply->id,
            'reportable_type' => Reply::class,
            'reason'          => $request->reason,
            'description'     => $request->description,
        ]);
        return back()->with('success', 'Laporan berhasil dikirim.');
    }
}
