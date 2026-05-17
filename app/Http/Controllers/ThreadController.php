<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Category;
use App\Models\Reply;
use App\Models\Tag;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    // NO __construct middleware — use route-level middleware in web.php

    public function index(Request $request)
    {
        $query = Thread::with(['user', 'category', 'tags']);

        $sort = $request->get('sort', 'latest');
        if ($sort === 'hot') {
            $query->where(function($q) {
                $q->where('is_hot', true)->orWhere('views_count', '>', 100);
            })->orderByDesc('replies_count');
        } elseif ($sort === 'unanswered') {
            $query->where('replies_count', 0)->orderByDesc('created_at');
        } else {
            $query->orderByDesc('is_pinned')->orderByDesc('created_at');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $threads = $query->paginate(20)->withQueryString();
        $category = null;
        $tag = null;
        return view('forum.index', compact('threads', 'category', 'tag'));
    }

    public function category(Category $category)
    {
        $threads = Thread::with(['user', 'category', 'tags'])
            ->where('category_id', $category->id)
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(20);
        $tag = null;
        return view('forum.index', compact('threads', 'category', 'tag'));
    }

    public function byTag(Tag $tag)
    {
        $threads = $tag->threads()->with(['user', 'category'])
            ->orderByDesc('created_at')
            ->paginate(20);
        $category = null;
        return view('forum.index', compact('threads', 'category', 'tag'));
    }

    public function show(Thread $thread, Request $request)
    {
        $viewedKey = "thread_viewed_{$thread->id}_" . (Auth::id() ?? $request->ip());
        if (!session()->has($viewedKey)) {
            $thread->incrementViews();
            session()->put($viewedKey, true);
        }

        $sort = $request->get('sort', 'oldest');
        $repliesQuery = $thread->replies()->with(['user']);
        if ($sort === 'newest') $repliesQuery->orderByDesc('created_at');
        elseif ($sort === 'likes') $repliesQuery->orderByDesc('likes_count');
        else $repliesQuery->orderBy('created_at');

        $replies = $repliesQuery->paginate(20);

        // Sync actual replies_count if mismatched
        $actualCount = $thread->replies()->count();
        if ($thread->replies_count !== $actualCount) {
            $thread->update(['replies_count' => $actualCount]);
        }

        $relatedThreads = Thread::where('category_id', $thread->category_id)
            ->where('id', '!=', $thread->id)
            ->orderByDesc('created_at')
            ->take(5)->get();

        if (Auth::check()) Auth::user()->updateLastSeen();

        return view('threads.show', compact('thread', 'replies', 'relatedThreads'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('threads.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'min:10', 'max:200'],
            'body'        => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags'        => ['nullable', 'string', 'max:500'],
            'thumbnail'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:3072'],
        ]);

        $body = strip_tags($validated['body'], '<p><br><strong><em><u><s><h1><h2><h3><h4><ul><ol><li><blockquote><a><table><thead><tbody><tr><th><td><code><pre><img><figure><figcaption>');

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thread-thumbnails/' . date('Y/m'), 'public');
        }

        $thread = Thread::create([
            'user_id'         => Auth::id(),
            'category_id'     => $validated['category_id'],
            'title'           => $validated['title'],
            'body'            => $body,
            'thumbnail'       => $thumbnailPath,
            'is_pinned'       => Auth::user()->is_admin && $request->boolean('is_pinned'),
            'is_announcement' => Auth::user()->is_admin && $request->boolean('is_announcement'),
            'is_locked'       => Auth::user()->is_admin && $request->boolean('is_locked'),
            'is_hot'          => Auth::user()->is_admin && $request->boolean('is_hot'),
        ]);

        if ($request->filled('tags')) {
            $thread->syncTags($validated['tags']);
        }

        Auth::user()->addReputation(10);

        return redirect()->route('threads.show', $thread->slug)
            ->with('success', 'Thread berhasil dibuat!');
    }

    public function edit(Thread $thread)
    {
        $this->authorize('update', $thread);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('threads.create', compact('thread', 'categories'));
    }

    public function update(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'min:10', 'max:200'],
            'body'        => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags'        => ['nullable', 'string', 'max:500'],
        ]);

        $body = strip_tags($validated['body'], '<p><br><strong><em><u><s><h1><h2><h3><h4><ul><ol><li><blockquote><a><table><thead><tbody><tr><th><td><code><pre>');

        $thread->update([
            'title'           => $validated['title'],
            'body'            => $body,
            'category_id'     => $validated['category_id'],
            'is_pinned'       => Auth::user()->is_admin ? $request->boolean('is_pinned') : $thread->is_pinned,
            'is_announcement' => Auth::user()->is_admin ? $request->boolean('is_announcement') : $thread->is_announcement,
            'is_locked'       => Auth::user()->is_admin ? $request->boolean('is_locked') : $thread->is_locked,
            'is_hot'          => Auth::user()->is_admin ? $request->boolean('is_hot') : $thread->is_hot,
        ]);

        if ($request->has('tags')) {
            $thread->syncTags($validated['tags'] ?? '');
        }

        return redirect()->route('threads.show', $thread->slug)
            ->with('success', 'Thread berhasil diperbarui!');
    }

    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->delete();
        return redirect()->route('home')->with('success', 'Thread berhasil dihapus!');
    }

    public function like(Thread $thread)
    {
        $user = Auth::user();
        $existing = $thread->likes()->where('user_id', $user->id)->first();
        if ($existing) {
            $existing->delete();
            $thread->decrement('likes_count');
        } else {
            $thread->likes()->create(['user_id' => $user->id]);
            $thread->increment('likes_count');
            if ($thread->user_id !== $user->id) {
                $thread->user->addReputation(2);
            }
        }
        return back();
    }

    public function report(Request $request, Thread $thread)
    {
        $request->validate(['reason' => 'required|string|max:255']);
        Report::create([
            'reporter_id'     => auth()->id(),
            'reportable_id'   => $thread->id,
            'reportable_type' => Thread::class,
            'reason'          => $request->reason,
            'description'     => $request->description,
        ]);
        return back()->with('success', 'Laporan berhasil dikirim.');
    }
}
