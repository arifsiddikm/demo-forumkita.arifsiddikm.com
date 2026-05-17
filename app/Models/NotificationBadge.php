<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'forum_notifications';
    protected $fillable = ['user_id', 'actor_id', 'type', 'data', 'read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime', 'data' => 'array'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function actor() { return $this->belongsTo(User::class, 'actor_id'); }

    public function isRead(): bool { return !is_null($this->read_at); }
    public function markAsRead(): void { $this->update(['read_at' => now()]); }

    public function getMessageAttribute(): string
    {
        $actor = $this->actor?->username ?? 'Seseorang';
        $data = $this->data;
        return match($this->type) {
            'reply' => "{$actor} membalas thread kamu: " . ($data['thread_title'] ?? ''),
            'like_thread' => "{$actor} menyukai thread kamu",
            'like_reply' => "{$actor} menyukai balasan kamu",
            'solution' => "Balasanmu ditandai sebagai solusi!",
            'mention' => "{$actor} menyebut kamu dalam thread",
            default => "Kamu punya notifikasi baru",
        };
    }
}

class Badge extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'color', 'condition_type', 'condition_value'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')->withTimestamps();
    }
}
