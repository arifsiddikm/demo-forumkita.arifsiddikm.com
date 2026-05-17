<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Thread, User, Reply, Category, Tag, Report};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        $stats = [
            'users' => User::count(),
            'threads' => Thread::count(),
            'replies' => Reply::count(),
            'pendingReports' => Report::pending()->count(),
            'newUsersToday' => User::whereDate('created_at', today())->count(),
            'newThreadsToday' => Thread::whereDate('created_at', today())->count(),
            'newRepliesToday' => Reply::whereDate('created_at', today())->count(),
        ];

        // Last 30 days chart data
        $days = collect(range(29, 0))->map(fn($i) => now()->subDays($i));

        $chartLabels = $days->map(fn($d) => $d->format('d/m'))->toArray();

        $chartThreads = $days->map(fn($d) => Thread::whereDate('created_at', $d)->count())->toArray();
        $chartReplies = $days->map(fn($d) => Reply::whereDate('created_at', $d)->count())->toArray();
        $chartUsers = $days->map(fn($d) => User::whereDate('created_at', $d)->count())->toArray();

        $topCategories = Category::withCount('threads')->orderByDesc('threads_count')->take(6)->get();
        $latestUsers = User::latest()->take(8)->get();
        $latestReports = Report::with('reporter')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'stats', 'chartLabels', 'chartThreads', 'chartReplies', 'chartUsers',
            'topCategories', 'latestUsers', 'latestReports'
        ));
    }
}
