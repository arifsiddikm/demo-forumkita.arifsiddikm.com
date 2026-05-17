<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'body',
        'views_count', 'replies_count', 'likes_count', 'thumbnail',
        'is_pinned', 'is_hot', 'is_locked', 'is_solved',
        'is_announcement', 'last_reply_at', 'last_reply_user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_hot' => 'boolean',
            'is_locked' => 'boolean',
            'is_solved' => 'boolean',
            'is_announcement' => 'boolean',
            'last_reply_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($thread) {
            $thread->slug = $thread->generateUniqueSlug($thread->title);
        });

        static::updating(function ($thread) {
            if ($thread->isDirty('title')) {
                $thread->slug = $thread->generateUniqueSlug($thread->title, $thread->id);
            }
        });

        static::deleted(function ($thread) {
            $thread->replies()->delete();
            $thread->likes()->delete();
            $thread->tags()->detach();
        });
    }

    public function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $count = 0;
        $original = $slug;

        while (true) {
            $query = static::where('slug', $slug);
            if ($excludeId) $query->where('id', '!=', $excludeId);
            if (!$query->exists()) break;
            $slug = $original . '-' . ++$count;
        }

        return $slug;
    }

    // ===== RELATIONSHIPS =====
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'thread_tags');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // ===== METHODS =====
    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function syncTags(string $tagsInput): void
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagsInput)));
        $tagIds = [];

        foreach ($tagNames as $name) {
            if (empty($name)) continue;
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'slug' => Str::slug($name)]
            );
            $tagIds[] = $tag->id;
        }

        $this->tags()->sync($tagIds);
    }

    // ===== SCOPES =====
    public function scopeHot($query)
    {
        return $query->where('is_hot', true)->orWhere('views_count', '>', 500)->orderByDesc('replies_count');
    }



    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeAnnouncement($query)
    {
        return $query->where('is_announcement', true);
    }

    public function scopeUnanswered($query)
    {
        return $query->where('replies_count', 0);
    }

    // ===== ROUTE KEY =====
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
