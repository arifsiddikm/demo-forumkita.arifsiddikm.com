<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->search, fn($q) =>
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('username', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%")
        )
        ->when($request->filter === 'banned', fn($q) => $q->where('is_banned', true))
        ->when($request->filter === 'admin', fn($q) => $q->where('is_admin', true))
        ->latest()
        ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['threads', 'replies']);
        return view('admin.users.show', compact('user'));
    }

    public function ban(Request $request, User $user)
    {
        $request->validate(['reason' => 'required|string|max:255']);
        if ($user->is_admin) return back()->with('error', 'Tidak bisa ban admin.');
        $user->update(['is_banned' => true, 'ban_reason' => $request->reason]);
        return back()->with('success', "Pengguna {$user->username} berhasil di-ban.");
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false, 'ban_reason' => null]);
        return back()->with('success', "Pengguna {$user->username} berhasil di-unban.");
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Tidak bisa ubah status admin diri sendiri.');
        $user->update(['is_admin' => !$user->is_admin]);
        $status = $user->is_admin ? 'dijadikan admin' : 'dicabut hak admin';
        return back()->with('success', "Pengguna {$user->username} berhasil {$status}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Tidak bisa hapus akun sendiri.');
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
