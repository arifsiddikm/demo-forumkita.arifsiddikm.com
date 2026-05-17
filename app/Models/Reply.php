<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ForumNotification;

class Reply extends Model
{
    protected $fillable = [
        'thread_id', 'user_id', 'body',
        'quoted_user', 'quoted_content',
        'is_solution', 'likes_count',
    ];

    protected function casts(): array
    {
        return ['is_solution' => 'boolean'];
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
            $reply->thread->update(['last_reply_at' => now(), 'last_reply_user_id' => $reply->user_id]);
            $reply->user->addReputation(5);
            // Notify thread author
            if ($reply->thread->user_id !== $reply->user_id) {
                ForumNotification::create([
                    'user_id'  => $reply->thread->user_id,
                    'actor_id' => $reply->user_id,
                    'type'     => 'reply',
                    'data'     => json_encode([
                        'thread_id'    => $reply->thread_id,
                        'thread_title' => $reply->thread->title,
                        'thread_slug'  => $reply->thread->slug,
                        'reply_id'     => $reply->id,
                    ]),
                ]);
                cache()->forget("user_notif_count_{$reply->thread->user_id}");
            }
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
            $reply->likes()->delete();
        });
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
