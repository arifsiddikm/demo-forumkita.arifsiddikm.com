@extends('layouts.app')
@section('title', $thread->title)
@section('meta_description', Str::limit(strip_tags($thread->body), 160))

@section('content')
<div style="display:grid;grid-template-columns:1fr 280px;gap:24px;align-items:start;" class="thread-grid">

    {{-- Main --}}
    <div>
        {{-- Breadcrumb --}}
        <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--fk-gray-500);margin-bottom:16px;flex-wrap:wrap;">
            <a href="{{ route('home') }}" style="color:var(--fk-blue);text-decoration:none;font-weight:600;">Home</a>
            <i class="fa fa-chevron-right" style="font-size:0.65rem;"></i>
            <a href="{{ route('forum.index') }}" style="color:var(--fk-blue);text-decoration:none;font-weight:600;">Forum</a>
            @if($thread->category)
            <i class="fa fa-chevron-right" style="font-size:0.65rem;"></i>
            <a href="{{ route('forum.category', $thread->category->slug) }}" style="color:var(--fk-blue);text-decoration:none;font-weight:600;">{{ $thread->category->name }}</a>
            @endif
            <i class="fa fa-chevron-right" style="font-size:0.65rem;"></i>
            <span style="color:var(--fk-gray-600);">{{ Str::limit($thread->title, 50) }}</span>
        </div>

        {{-- Thread Post --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header" style="padding:20px 24px;">
                <div style="width:100%;">
                    <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;">
                        @if($thread->category)
                            <span class="badge" style="background:{{ $thread->category->color ?? '#2563EB' }}20;color:{{ $thread->category->color ?? '#2563EB' }};">
                                <i class="{{ $thread->category->icon ?? 'fa fa-folder' }}" style="font-size:0.65rem;"></i> {{ $thread->category->name }}
                            </span>
                        @endif
                        @if($thread->is_pinned) <span class="badge badge-yellow"><i class="fa fa-thumbtack"></i> Pinned</span> @endif
                        @if($thread->is_hot) <span class="badge badge-hot"><i class="fa fa-fire"></i> Hot</span> @endif
                        @if($thread->is_solved) <span class="badge badge-green"><i class="fa fa-check"></i> Solved</span> @endif
                        @if($thread->is_locked) <span class="badge badge-red"><i class="fa fa-lock"></i> Locked</span> @endif
                    </div>
                    <h1 style="font-size:1.4rem;font-weight:800;color:var(--fk-gray-900);margin:0 0 10px;line-height:1.3;">{{ $thread->title }}</h1>
                    <div style="display:flex;align-items:center;gap:14px;font-size:0.8rem;color:var(--fk-gray-500);flex-wrap:wrap;">
                        <span><i class="fa fa-eye"></i> {{ number_format($thread->views_count) }} dilihat</span>
                        <span><i class="fa fa-reply"></i> {{ $replies->total() }} balasan</span>
                        <span><i class="fa fa-thumbs-up"></i> {{ $thread->likes_count ?? 0 }} suka</span>
                        <span><i class="fa fa-clock"></i> {{ $thread->created_at->isoFormat('D MMMM Y, HH:mm') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body" style="padding:24px;">
                <div style="display:flex;gap:20px;">
                    {{-- Author sidebar --}}
                    <div style="flex-shrink:0;width:100px;text-align:center;" class="hide-mobile">
                        @if($thread->user->avatar)
                            <img src="{{ Storage::url($thread->user->avatar) }}" class="avatar avatar-xl" style="margin:0 auto 8px;" alt="">
                        @else
                            <div class="avatar avatar-xl avatar-placeholder" style="font-size:1.2rem;margin:0 auto 8px;">{{ strtoupper(substr($thread->user->name,0,2)) }}</div>
                        @endif
                        <a href="{{ route('profile.show', $thread->user->username) }}" style="font-weight:800;font-size:0.85rem;color:var(--fk-blue);text-decoration:none;display:block;">{{ $thread->user->username }}</a>
                        <div style="font-size:0.72rem;color:var(--fk-gray-500);margin-top:2px;">{{ $thread->user->level_label }}</div>
                        <div class="badge badge-blue" style="margin-top:6px;justify-content:center;">{{ $thread->user->reputation }} poin</div>
                        @if($thread->user->is_admin)
                            <div class="badge" style="margin-top:4px;justify-content:center;background:#7C3AED20;color:#7C3AED;">Admin</div>
                        @endif
                    </div>

                    <div style="flex:1;min-width:0;">
                        {{-- Mobile author --}}
                        <div class="show-mobile" style="display:flex;align-items:center;gap:8px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--fk-gray-100);">
                            <div class="avatar avatar-sm avatar-placeholder" style="font-size:0.65rem;">{{ strtoupper(substr($thread->user->name,0,2)) }}</div>
                            <div>
                                <a href="{{ route('profile.show', $thread->user->username) }}" style="font-weight:700;font-size:0.875rem;color:var(--fk-blue);text-decoration:none;">{{ $thread->user->username }}</a>
                                <div style="font-size:0.72rem;color:var(--fk-gray-500);">{{ $thread->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        {{-- Thread image thumbnail --}}
                        @if($thread->thumbnail)
                        <div style="margin-bottom:16px;">
                            <img src="{{ Storage::url($thread->thumbnail) }}" alt="Thumbnail" style="max-width:100%;border-radius:10px;max-height:400px;object-fit:cover;">
                        </div>
                        @endif

                        <div class="prose-content" style="line-height:1.8;color:var(--fk-gray-700);font-size:0.95rem;">{!! $thread->body !!}</div>

                        @if($thread->tags && $thread->tags->count() > 0)
                        <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--fk-gray-100);display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                            <span style="font-size:0.8rem;color:var(--fk-gray-400);font-weight:600;"><i class="fa fa-tags"></i></span>
                            @foreach($thread->tags as $tag)
                                <a href="{{ route('forum.tag', $tag->slug) }}" class="badge badge-gray" style="text-decoration:none;">#{{ $tag->name }}</a>
                            @endforeach
                        </div>
                        @endif

                        {{-- Thread Actions --}}
                        <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--fk-gray-100);display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                            @auth
                            <form action="{{ route('threads.like', $thread->slug) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn {{ $thread->isLikedBy(auth()->user()) ? 'btn-primary' : 'btn-outline' }} btn-sm">
                                    <i class="fa fa-thumbs-up"></i> Suka ({{ $thread->likes_count }})
                                </button>
                            </form>
                            <button onclick="scrollToReply()" class="btn btn-outline btn-sm">
                                <i class="fa fa-reply"></i> Balas
                            </button>
                            @if(auth()->id() === $thread->user_id || auth()->user()->is_admin)
                                <a href="{{ route('threads.edit', $thread->slug) }}" class="btn btn-outline btn-sm"><i class="fa fa-pencil"></i> Edit</a>
                                <form action="{{ route('threads.destroy', $thread->slug) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus thread ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Replies --}}
        <div id="replies">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
                <h3 style="font-size:1rem;font-weight:700;color:var(--fk-gray-800);margin:0;">
                    <i class="fa fa-comments" style="color:var(--fk-blue);"></i>
                    {{ $replies->total() }} Balasan
                </h3>
                <form method="GET" action="{{ route('threads.show', $thread->slug) }}" style="display:flex;gap:6px;align-items:center;">
                    <select name="sort" onchange="this.form.submit()" class="form-select" style="font-size:0.8rem;padding:6px 10px;">
                        <option value="oldest" {{ request('sort','oldest')==='oldest'?'selected':'' }}>Terlama</option>
                        <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>Terbaru</option>
                        <option value="likes" {{ request('sort')==='likes'?'selected':'' }}>Terpopuler</option>
                    </select>
                </form>
            </div>

            @forelse($replies as $reply)
            <div class="card" style="margin-bottom:12px;{{ $reply->is_solution ? 'border:2px solid #10B981;' : '' }}" id="reply-{{ $reply->id }}">
                @if($reply->is_solution)
                <div style="background:#D1FAE5;padding:6px 16px;font-size:0.78rem;font-weight:700;color:#065F46;border-bottom:1px solid #6EE7B7;">
                    <i class="fa fa-check-circle"></i> Balasan Terbaik (Solusi)
                </div>
                @endif
                <div class="card-body" style="padding:16px 20px;">
                    <div style="display:flex;gap:14px;">
                        {{-- Reply author --}}
                        <div style="flex-shrink:0;text-align:center;width:70px;" class="hide-mobile">
                            @if($reply->user->avatar)
                                <img src="{{ Storage::url($reply->user->avatar) }}" class="avatar avatar-md" style="margin:0 auto 6px;" alt="">
                            @else
                                <div class="avatar avatar-md avatar-placeholder" style="font-size:0.85rem;margin:0 auto 6px;">{{ strtoupper(substr($reply->user->name,0,2)) }}</div>
                            @endif
                            <a href="{{ route('profile.show', $reply->user->username) }}" style="font-weight:700;font-size:0.75rem;color:var(--fk-blue);text-decoration:none;word-break:break-all;">{{ $reply->user->username }}</a>
                            <div style="font-size:0.65rem;color:var(--fk-gray-400);margin-top:2px;">{{ $reply->user->level_label }}</div>
                        </div>

                        <div style="flex:1;min-width:0;">
                            {{-- Mobile reply author --}}
                            <div class="show-mobile" style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                                <div class="avatar avatar-sm avatar-placeholder" style="font-size:0.65rem;">{{ strtoupper(substr($reply->user->name,0,2)) }}</div>
                                <a href="{{ route('profile.show', $reply->user->username) }}" style="font-weight:700;font-size:0.85rem;color:var(--fk-blue);text-decoration:none;">{{ $reply->user->username }}</a>
                            </div>

                            {{-- Quote --}}
                            @if($reply->quoted_user)
                            <div class="quote-block" style="margin-bottom:12px;">
                                <div style="font-size:0.75rem;font-weight:700;color:var(--fk-blue);margin-bottom:4px;"><i class="fa fa-quote-left"></i> {{ $reply->quoted_user }}</div>
                                <div style="font-size:0.85rem;color:var(--fk-gray-600);">{!! Str::limit(strip_tags($reply->quoted_content), 200) !!}</div>
                            </div>
                            @endif

                            <div class="prose-content" style="line-height:1.7;font-size:0.9rem;color:var(--fk-gray-700);">{!! $reply->body !!}</div>

                            <div style="margin-top:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                                <span style="font-size:0.75rem;color:var(--fk-gray-400);">
                                    <i class="fa fa-clock"></i> {{ $reply->created_at->diffForHumans() }}
                                </span>
                                <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                                    {{-- Like reply --}}
                                    @auth
                                    <form action="{{ route('replies.like', $reply->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm" style="font-size:0.75rem;padding:4px 10px;">
                                            <i class="fa fa-thumbs-up"></i> {{ $reply->likes_count }}
                                        </button>
                                    </form>

                                    {{-- Quote --}}
                                    <button onclick="setQuote('{{ addslashes($reply->user->username) }}', '{{ addslashes(Str::limit(strip_tags($reply->body), 150)) }}')" class="btn btn-outline btn-sm" style="font-size:0.75rem;padding:4px 10px;">
                                        <i class="fa fa-quote-right"></i> Quote
                                    </button>

                                    {{-- Mark solution (thread owner only) --}}
                                    @if(auth()->id() === $thread->user_id || auth()->user()->is_admin)
                                    <form action="{{ route('replies.solution', $reply->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn {{ $reply->is_solution ? 'btn-success' : 'btn-outline' }} btn-sm" style="font-size:0.75rem;padding:4px 10px;">
                                            <i class="fa fa-check"></i> {{ $reply->is_solution ? 'Solusi ✓' : 'Tandai Solusi' }}
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Edit/Delete (inline modal, no replies.edit route needed) --}}
                                    @if(auth()->id() === $reply->user_id || auth()->user()->is_admin)
                                    <button onclick="openEditReply({{ $reply->id }}, `{!! addslashes(htmlspecialchars($reply->body, ENT_QUOTES)) !!}`)" class="btn btn-outline btn-sm" style="font-size:0.75rem;padding:4px 10px;">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus balasan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="font-size:0.75rem;padding:4px 10px;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:48px;background:white;border:1px solid var(--fk-gray-200);border-radius:14px;">
                <i class="fa fa-comments" style="font-size:2.5rem;color:var(--fk-gray-300);margin-bottom:12px;display:block;"></i>
                <p style="color:var(--fk-gray-500);margin:0;">Belum ada balasan. Jadilah yang pertama membalas!</p>
            </div>
            @endforelse

            {{-- Pagination --}}
            @if($replies->hasPages())
            <div style="margin-top:16px;">{{ $replies->appends(request()->except('page'))->links() }}</div>
            @endif
        </div>

        {{-- Reply Form --}}
        <div class="card" id="reply-form" style="margin-top:20px;">
            <div class="card-header">
                <h3><i class="fa fa-reply" style="color:var(--fk-blue);margin-right:8px;"></i>Tulis Balasan</h3>
            </div>
            <div class="card-body" style="padding:24px;">
                @auth
                    @if($thread->is_locked)
                        <div class="alert alert-warning"><i class="fa fa-lock"></i> Thread dikunci. Tidak bisa menambah balasan.</div>
                    @else
                        <form action="{{ route('replies.store', $thread->slug) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div id="quotePreview" style="display:none;margin-bottom:12px;">
                                    <div class="quote-block" id="quoteDisplay"></div>
                                    <input type="hidden" name="quoted_user" id="quotedUser">
                                    <input type="hidden" name="quoted_content" id="quotedContent">
                                    <button type="button" onclick="clearQuote()" class="btn btn-outline btn-sm" style="margin-top:6px;"><i class="fa fa-xmark"></i> Hapus Quote</button>
                                </div>
                                <textarea name="body" id="replyEditor" class="form-textarea" placeholder="Tulis balasanmu di sini..." rows="6">{{ old('body') }}</textarea>
                                @error('body') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:12px;">
                                <button type="reset" class="btn btn-outline btn-sm">Reset</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim Balasan</button>
                            </div>
                        </form>
                    @endif
                @else
                    <div style="text-align:center;padding:24px;">
                        <p style="color:var(--fk-gray-500);margin-bottom:14px;">Kamu harus masuk untuk membalas thread ini.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary"><i class="fa fa-right-to-bracket"></i> Masuk</a>
                        <span style="color:var(--fk-gray-400);margin:0 10px;">atau</span>
                        <a href="{{ route('register') }}" class="btn btn-yellow"><i class="fa fa-user-plus"></i> Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <aside>
        <div class="sidebar-section">
            <div class="sidebar-header"><i class="fa fa-info-circle"></i> Info Thread</div>
            <div class="sidebar-body">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:var(--fk-gray-500);">Dibuat</span>
                        <span style="font-weight:600;color:var(--fk-gray-700);">{{ $thread->created_at->isoFormat('D MMM Y') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:var(--fk-gray-500);">Balasan</span>
                        <span style="font-weight:600;color:var(--fk-gray-700);">{{ $replies->total() }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:var(--fk-gray-500);">Dilihat</span>
                        <span style="font-weight:600;color:var(--fk-gray-700);">{{ number_format($thread->views_count) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:var(--fk-gray-500);">Status</span>
                        <span>
                            @if($thread->is_solved) <span class="badge badge-green">Solved</span>
                            @elseif($thread->is_locked) <span class="badge badge-red">Locked</span>
                            @else <span class="badge badge-blue">Aktif</span>
                            @endif
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:var(--fk-gray-500);">Oleh</span>
                        <a href="{{ route('profile.show', $thread->user->username) }}" style="font-weight:600;color:var(--fk-blue);text-decoration:none;font-size:0.82rem;">{{ $thread->user->username }}</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Threads --}}
        @if($relatedThreads->count())
        <div class="sidebar-section">
            <div class="sidebar-header"><i class="fa fa-layer-group"></i> Thread Terkait</div>
            <div class="sidebar-body" style="padding:0;">
                @foreach($relatedThreads as $related)
                <a href="{{ route('threads.show', $related->slug) }}" style="display:block;padding:10px 14px;border-bottom:1px solid var(--fk-gray-100);text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='var(--fk-gray-50)'" onmouseout="this.style.background='transparent'">
                    <div style="font-size:0.82rem;font-weight:600;color:var(--fk-gray-800);line-height:1.4;margin-bottom:4px;">{{ Str::limit($related->title, 60) }}</div>
                    <div style="font-size:0.72rem;color:var(--fk-gray-400);"><i class="fa fa-reply"></i> {{ $related->replies_count }} balasan</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </aside>
</div>

{{-- Edit Reply Modal --}}
<div id="editReplyModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:500;align-items:center;justify-content:center;padding:16px;">
    <div style="background:white;border-radius:16px;padding:28px;width:100%;max-width:600px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:1.1rem;font-weight:700;color:var(--fk-gray-900);margin:0;"><i class="fa fa-pencil" style="color:var(--fk-blue);"></i> Edit Balasan</h3>
            <button onclick="closeEditReply()" style="background:none;border:none;cursor:pointer;color:var(--fk-gray-400);font-size:1.2rem;"><i class="fa fa-xmark"></i></button>
        </div>
        <form id="editReplyForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <textarea name="body" id="editReplyBody" class="form-textarea" rows="6" style="width:100%;"></textarea>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;">
                <button type="button" onclick="closeEditReply()" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<style>
@media(max-width:768px){.thread-grid{grid-template-columns:1fr!important}aside{display:none}}
.quote-block{background:var(--fk-gray-50);border-left:3px solid var(--fk-blue);padding:10px 14px;border-radius:0 8px 8px 0;font-size:0.85rem;color:var(--fk-gray-600);}
.prose-content img{max-width:100%;border-radius:8px;margin:8px 0;}
.prose-content h1,.prose-content h2,.prose-content h3{color:var(--fk-gray-900);margin:16px 0 8px;}
.prose-content ul,.prose-content ol{padding-left:20px;margin:8px 0;}
.prose-content li{margin-bottom:4px;}
.prose-content code{background:var(--fk-gray-100);padding:2px 6px;border-radius:4px;font-size:0.85em;}
.prose-content pre{background:var(--fk-gray-900);color:#e2e8f0;padding:16px;border-radius:8px;overflow-x:auto;font-size:0.85rem;}
.prose-content blockquote{border-left:3px solid var(--fk-blue);padding:8px 14px;background:var(--fk-gray-50);margin:8px 0;border-radius:0 8px 8px 0;}
.prose-content table{width:100%;border-collapse:collapse;margin:12px 0;}
.prose-content th,.prose-content td{border:1px solid var(--fk-gray-200);padding:8px 12px;text-align:left;}
.prose-content th{background:var(--fk-gray-50);font-weight:700;}
</style>

<script>
function scrollToReply() {
    document.getElementById('reply-form').scrollIntoView({behavior:'smooth'});
    setTimeout(() => document.getElementById('replyEditor')?.focus(), 400);
}

function setQuote(username, content) {
    document.getElementById('quotedUser').value = username;
    document.getElementById('quotedContent').value = content;
    document.getElementById('quoteDisplay').innerHTML = '<strong>' + username + ':</strong> ' + content;
    document.getElementById('quotePreview').style.display = 'block';
    scrollToReply();
}

function clearQuote() {
    document.getElementById('quotedUser').value = '';
    document.getElementById('quotedContent').value = '';
    document.getElementById('quotePreview').style.display = 'none';
}

function openEditReply(id, body) {
    document.getElementById('editReplyForm').action = '/reply/' + id;
    document.getElementById('editReplyBody').value = body.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&').replace(/&quot;/g,'"');
    document.getElementById('editReplyModal').style.display = 'flex';
}

function closeEditReply() {
    document.getElementById('editReplyModal').style.display = 'none';
}

// Close modal on backdrop click
document.getElementById('editReplyModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditReply();
});
</script>
@endsection
