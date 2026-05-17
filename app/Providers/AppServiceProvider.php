<?php

namespace App\Providers;

use App\Models\Thread;
use App\Models\Reply;
use App\Policies\ThreadPolicy;
use App\Policies\ReplyPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Thread::class => ThreadPolicy::class,
        Reply::class  => ReplyPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fix: setLocale untuk Carbon
        Carbon::setLocale(config('app.locale', 'id'));
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'id');

        // Register policies
        Gate::policy(Thread::class, ThreadPolicy::class);
        Gate::policy(Reply::class, ReplyPolicy::class);

        // Tailwind pagination views
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');
    }
}
