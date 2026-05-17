<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password',
        'gender', 'avatar', 'bio', 'location', 'website',
        'signature', 'reputation', 'is_admin', 'is_banned',
        'ban_reason', 'last_seen_at', 'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'is_admin' => 'boolean',
            'is_banned' => 'boolean',
        ];
    }

    // ===== RELATIONSHIPS =====
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function forum_notifications()
    {
        return $this->hasMany(\App\Models\ForumNotification::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('awarded_at');
    }

    // ===== ACCESSORS =====
    public function getLevelLabelAttribute(): string
    {
        return match(true) {
            $this->reputation >= 10000 => 'Grand Master',
            $this->reputation >= 5000 => 'Master',
            $this->reputation >= 2000 => 'Expert',
            $this->reputation >= 1000 => 'Senior',
            $this->reputation >= 500 => 'Member',
            $this->reputation >= 100 => 'Junior',
            default => 'Newbie',
        };
    }

    public function getLevelColorAttribute(): string
    {
        return match(true) {
            $this->reputation >= 10000 => '#EF4444',
            $this->reputation >= 5000 => '#EAB308',
            $this->reputation >= 2000 => '#8B5CF6',
            $this->reputation >= 1000 => '#3B82F6',
            $this->reputation >= 500 => '#22C55E',
            default => '#64748B',
        };
    }

    // ===== METHODS =====
    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) < 5;
    }

    public function updateLastSeen(): void
    {
        $this->update(['last_seen_at' => now()]);
    }

    public function addReputation(int $points): void
    {
        $this->increment('reputation', $points);
    }

    public function unreadNotificationsCount(): int
    {
        return Cache::remember("user_notif_count_{$this->id}", 60, function () {
            return \App\Models\ForumNotification::where('user_id', $this->id)
                ->whereNull('read_at')
                ->count();
        });
    }

    public function getThreadsCountAttribute(): int
    {
        return $this->threads()->count();
    }

    // ===== SCOPES =====
    public function scopeActive($query)
    {
        return $query->where('is_banned', false);
    }

    public function scopeOnline($query)
    {
        return $query->where('last_seen_at', '>=', now()->subMinutes(5));
    }
}
