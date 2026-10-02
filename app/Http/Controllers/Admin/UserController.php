<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->latest('user_id')
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_if($user->isAdmin() || $request->user()->is($user), 403);

        $validated = $request->validate([
            'role' => 'required|in:Customer,Seller',
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Peran pengguna berhasil diperbarui.');
    }
}
