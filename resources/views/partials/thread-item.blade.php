<div class="thread-item" style="display:flex;gap:14px;align-items:flex-start;text-decoration:none;color:inherit;">
    {{-- Author Avatar --}}
    <div style="flex-shrink:0;">
        @if($thread->user && $thread->user->avatar)
            <img src="{{ asset('storage/avatars/'.$thread->user->avatar) }}" class="avatar avatar-md" alt="">
        @else
            <div class="avatar avatar-md avatar-placeholder" style="font-size:0.75rem;">
                {{ $thread->user ? strtoupper(substr($thread->user->username,0,2)) : 'NA' }}
            </div>
        @endif
    </div>

    {{-- Thread Content --}}
    <div style="flex:1;min-width:0;">
        {{-- Badges row --}}
        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:6px;">
            @if($thread->category)
                <a href="{{ route('forum.category', $thread->category->slug) }}"
                   class="badge"
                   style="background:{{ $thread->category->color ?? '#2563EB' }}20;color:{{ $thread->category->color ?? '#2563EB' }};text-decoration:none;"
                   onclick="event.stopPropagation()">
                    <i class="{{ $thread->category->icon ?? 'fa fa-folder' }}" style="font-size:0.65rem;"></i>
                    {{ $thread->category->name }}
                </a>
            @endif
            @if($thread->is_pinned)
                <span class="badge badge-yellow"><i class="fa fa-thumbtack" style="font-size:0.65rem;"></i> Pinned</span>
            @endif
            @if($thread->is_hot)
                <span class="badge badge-hot"><i class="fa fa-fire" style="font-size:0.65rem;"></i> Hot</span>
            @endif
            @if($thread->is_solved)
                <span class="badge badge-green"><i class="fa fa-check" style="font-size:0.65rem;"></i> Solved</span>
            @endif
            @if($thread->is_announcement)
                <span class="badge" style="background:#7C3AED20;color:#7C3AED;"><i class="fa fa-bullhorn" style="font-size:0.65rem;"></i> Pengumuman</span>
            @endif
        </div>

        {{-- Title --}}
        <a href="{{ route('threads.show', $thread->slug) }}" class="thread-title" style="display:block;text-decoration:none;margin-bottom:6px;">
            {{ $thread->title }}
        </a>

        {{-- Excerpt --}}
        @if(!isset($hideExcerpt))
        <p style="font-size:0.82rem;color:var(--fk-gray-500);margin:0 0 8px;line-height:1.5;">
            {{ Str::limit(strip_tags($thread->body), 120) }}
        </p>
        @endif

        {{-- Meta --}}
        <div class="thread-meta">
            <span>
                <i class="fa fa-user" style="color:var(--fk-blue);font-size:0.7rem;"></i>
                <a href="{{ route('profile.show', $thread->user->username ?? '#') }}" style="color:var(--fk-blue);font-weight:700;text-decoration:none;" onclick="event.stopPropagation()">
                    {{ $thread->user->username ?? 'Unknown' }}
                </a>
            </span>
            <span>
                <i class="fa fa-clock" style="font-size:0.7rem;"></i>
                {{ $thread->created_at->diffForHumans() }}
            </span>
            <span>
                <i class="fa fa-eye" style="font-size:0.7rem;"></i>
                {{ number_format($thread->views_count) }}
            </span>
            <span>
                <i class="fa fa-reply" style="font-size:0.7rem;"></i>
                {{ number_format($thread->replies_count) }} balasan
            </span>
            <span>
                <i class="fa fa-thumbs-up" style="font-size:0.7rem;"></i>
                {{ $thread->likes_count ?? 0 }}
            </span>
        </div>

        {{-- Tags --}}
        @if($thread->tags && $thread->tags->count() > 0)
        <div style="margin-top:8px;display:flex;gap:4px;flex-wrap:wrap;">
            @foreach($thread->tags->take(4) as $tag)
                <a href="{{ route('forum.tag', $tag->slug) }}" class="badge badge-gray" style="text-decoration:none;font-size:0.7rem;" onclick="event.stopPropagation()">#{{ $tag->name }}</a>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Right Stats --}}
    <div style="flex-shrink:0;text-align:center;display:flex;flex-direction:column;align-items:center;gap:4px;" class="hide-mobile">
        <div style="background:{{ $thread->replies_count > 0 ? 'var(--fk-blue)' : 'var(--fk-gray-100)' }};color:{{ $thread->replies_count > 0 ? 'white' : 'var(--fk-gray-400)' }};border-radius:8px;padding:6px 12px;min-width:52px;">
            <div style="font-weight:800;font-size:1rem;font-family:'Plus Jakarta Sans',sans-serif;">{{ $thread->replies_count }}</div>
            <div style="font-size:0.65rem;font-weight:600;">BALASAN</div>
        </div>
        <div style="font-size:0.72rem;color:var(--fk-gray-400);">
            <i class="fa fa-eye"></i> {{ number_format($thread->views_count) }}
        </div>
    </div>
</div>
