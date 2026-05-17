<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug = $m->slug ?: Str::slug($m->name));
    }

    public function threads()
    {
        return $this->belongsToMany(Thread::class, 'thread_tags');
    }

    public function getRouteKeyName(): string { return 'slug'; }
}
