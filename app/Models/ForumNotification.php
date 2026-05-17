<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumNotification extends Model
{
    protected $table = 'forum_notifications';

    protected $fillable = [
        'user_id', 'actor_id', 'type', 'data', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data'    => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function getMessageAttribute(): string
    {
        return match($this->type) {
            'reply'    => 'membalas thread Anda.',
            'like'     => 'menyukai postingan Anda.',
            'mention'  => 'menyebut Anda dalam sebuah thread.',
            'solution' => 'menandai jawaban Anda sebagai solusi.',
            default    => 'berinteraksi dengan Anda.',
        };
    }
}
