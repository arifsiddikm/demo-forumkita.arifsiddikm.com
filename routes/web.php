<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\ThreadController as AdminThread;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\ImageUploadController;

/*
|--------------------------------------------------------------------------
| Image Upload Routes (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/upload/image', [ImageUploadController::class, 'upload'])->name('upload.image');
    Route::post('/upload/thumbnail', [ImageUploadController::class, 'uploadThumbnail'])->name('upload.thumbnail');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/leaderboard', [HomeController::class, 'leaderboard'])->name('leaderboard');
Route::get('/members', [HomeController::class, 'members'])->name('members');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/tos', [HomeController::class, 'tos'])->name('tos');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| Forum - Public
|--------------------------------------------------------------------------
*/
Route::get('/forum', [ThreadController::class, 'index'])->name('forum.index');
Route::get('/forum/hot', fn() => redirect()->route('forum.index', ['sort' => 'hot']))->name('threads.hot');
Route::get('/forum/terbaru', fn() => redirect()->route('forum.index', ['sort' => 'latest']))->name('threads.latest');
Route::get('/forum/kategori/{category:slug}', [ThreadController::class, 'category'])->name('forum.category');
Route::get('/forum/tag/{tag:slug}', [ThreadController::class, 'byTag'])->name('forum.tag');
Route::get('/thread/{thread:slug}', [ThreadController::class, 'show'])->name('threads.show');

/*
|--------------------------------------------------------------------------
| Forum - Auth Required
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Threads
    Route::get('/buat-thread', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/thread', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/thread/{thread:slug}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::put('/thread/{thread:slug}', [ThreadController::class, 'update'])->name('threads.update');
    Route::delete('/thread/{thread:slug}', [ThreadController::class, 'destroy'])->name('threads.destroy');
    Route::post('/thread/{thread:slug}/like', [ThreadController::class, 'like'])->name('threads.like');
    Route::post('/thread/{thread:slug}/report', [ThreadController::class, 'report'])->name('threads.report');

    // Replies
    Route::post('/thread/{thread:slug}/reply', [ReplyController::class, 'store'])->name('replies.store');
    Route::put('/reply/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/reply/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');
    Route::post('/reply/{reply}/like', [ReplyController::class, 'like'])->name('replies.like');
    Route::post('/reply/{reply}/solution', [ReplyController::class, 'markSolution'])->name('replies.solution');
    Route::post('/reply/{reply}/report', [ReplyController::class, 'report'])->name('replies.report');

    // Profile
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

// Profile show (public)
Route::get('/profil/{user:username}', [ProfileController::class, 'show'])->name('profile.show');

/*
|--------------------------------------------------------------------------
| Admin Routes - middleware at route level (Laravel 11 compatible)
|--------------------------------------------------------------------------
*/
Route::prefix('webmin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::get('/pengguna', [AdminUser::class, 'index'])->name('users.index');
    Route::get('/pengguna/{user}', [AdminUser::class, 'show'])->name('users.show');
    Route::put('/pengguna/{user}/ban', [AdminUser::class, 'ban'])->name('users.ban');
    Route::put('/pengguna/{user}/unban', [AdminUser::class, 'unban'])->name('users.unban');
    Route::put('/pengguna/{user}/admin', [AdminUser::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('/pengguna/{user}', [AdminUser::class, 'destroy'])->name('users.destroy');

    // Categories
    Route::resource('kategori', AdminCategory::class)->names([
        'index'   => 'categories.index',
        'create'  => 'categories.create',
        'store'   => 'categories.store',
        'show'    => 'categories.show',
        'edit'    => 'categories.edit',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);

    // Threads
    Route::get('/thread', [AdminThread::class, 'index'])->name('threads.index');
    Route::put('/thread/{thread}/pin', [AdminThread::class, 'pin'])->name('threads.pin');
    Route::put('/thread/{thread}/hot', [AdminThread::class, 'hot'])->name('threads.hot');
    Route::put('/thread/{thread}/lock', [AdminThread::class, 'lock'])->name('threads.lock');
    Route::put('/thread/{thread}/announce', [AdminThread::class, 'announce'])->name('threads.announce');
    Route::delete('/thread/{thread}', [AdminThread::class, 'destroy'])->name('threads.destroy');

    // Reports
    Route::get('/laporan', [AdminReport::class, 'index'])->name('reports.index');
    Route::put('/laporan/{report}/resolve', [AdminReport::class, 'resolve'])->name('reports.resolve');
    Route::put('/laporan/{report}/dismiss', [AdminReport::class, 'dismiss'])->name('reports.dismiss');
});
