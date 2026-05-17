<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Reply;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class HomeController extends Controller
{
    public function index()
    {
        // Always fetch fresh — no cache for objects that need relations
        $hotThreads = Thread::with(['user', 'category', 'tags'])
            ->orderByDesc('views_count')
            ->orderByDesc('replies_count')
            ->take(15)
            ->get();

        $latestThreads = Thread::with(['user', 'category', 'tags'])
            ->orderByDesc('created_at')
            ->take(15)
            ->get();

        $unansweredThreads = Thread::with(['user', 'category'])
            ->where('replies_count', 0)
            ->orderByDesc('created_at')
            ->take(15)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('threads')
            ->orderBy('sort_order')
            ->take(12)
            ->get();

        $popularTags = Tag::withCount('threads')
            ->orderByDesc('threads_count')
            ->take(20)
            ->get();

        $topMembers = User::where('is_banned', false)
            ->orderByDesc('reputation')
            ->take(10)
            ->get();

        $announcements = Thread::where('is_announcement', true)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $recentOnlineUsers = User::where('last_seen_at', '>=', now()->subMinutes(5))->take(20)->get();
        $onlineUsers       = $recentOnlineUsers->count();

        $totalUsers   = User::count();
        $totalThreads = Thread::count();
        $totalReplies = Reply::count();

        return view('home', compact(
            'hotThreads', 'latestThreads', 'unansweredThreads',
            'categories', 'popularTags', 'topMembers', 'announcements',
            'recentOnlineUsers', 'onlineUsers',
            'totalUsers', 'totalThreads', 'totalReplies'
        ));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $threads = collect();

        if (strlen($q) >= 3) {
            $threads = Thread::with(['user', 'category'])
                ->where(function($query) use ($q) {
                    $query->where('title', 'LIKE', "%{$q}%")
                          ->orWhere('body', 'LIKE', "%{$q}%");
                })
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString();
        }

        return view('search', compact('q', 'threads'));
    }

    public function leaderboard()
    {
        $members = User::where('is_banned', false)
            ->orderByDesc('reputation')
            ->paginate(50);
        return view('leaderboard', compact('members'));
    }

    public function members()
    {
        $members = User::where('is_banned', false)
            ->orderByDesc('created_at')
            ->paginate(24);
        return view('members', compact('members'));
    }

    public function about()   { return view('pages.about'); }
    public function tos()     { return view('pages.tos'); }
    public function privacy() { return view('pages.privacy'); }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:2000',
        ]);

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'noreply@arifsiddikm.com';
            $mail->Password   = 'SatuDua345!!';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->setFrom('noreply@arifsiddikm.com', 'ForumKita Contact');
            $mail->addAddress('arifsiddikmuharam@gmail.com');
            $mail->addReplyTo($validated['email'], $validated['name']);
            $mail->isHTML(true);
            $mail->Subject = '[ForumKita] ' . $validated['subject'];
            $mail->Body    = "<p><strong>Dari:</strong> {$validated['name']} ({$validated['email']})</p>"
                           . "<p><strong>Pesan:</strong><br>" . nl2br(e($validated['message'])) . "</p>";
            $mail->send();
        } catch (MailException $e) {
            return back()->with('error', 'Gagal mengirim pesan. Coba lagi nanti.')->withInput();
        }

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
