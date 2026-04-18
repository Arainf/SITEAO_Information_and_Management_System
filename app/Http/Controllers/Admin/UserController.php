<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', [
            'users'    => $users,
            'roles'    => [User::ROLE_ADMIN, User::ROLE_MODERATOR, User::ROLE_OFFICER, User::ROLE_MEMBER, User::ROLE_PENDING],
            'statuses' => [User::STATUS_ACTIVE, User::STATUS_INACTIVE],
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:admin,moderator,officer,member,pending'],
        ]);

        if ($user->role === User::ROLE_ADMIN && $request->role !== User::ROLE_ADMIN) {
            $adminCount = User::where('role', User::ROLE_ADMIN)->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot demote the last administrator.');
            }
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Role updated to {$request->role} for {$user->name}.");
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $newStatus = $user->status === User::STATUS_ACTIVE
            ? User::STATUS_INACTIVE
            : User::STATUS_ACTIVE;

        $user->update(['status' => $newStatus]);

        return back()->with('success', "User {$user->name} is now {$newStatus}.");
    }

    public function approve(User $user): RedirectResponse
    {
        if (! $user->isPending()) {
            return back()->with('error', 'User is not pending approval.');
        }

        $user->update(['role' => User::ROLE_MEMBER]);

        return back()->with('success', "{$user->name} has been approved as a Member.");
    }

    public function reject(User $user): RedirectResponse
    {
        if (! $user->isPending()) {
            return back()->with('error', 'User is not pending approval.');
        }

        $user->delete();

        return back()->with('success', 'Pending user has been rejected and removed.');
    }
}
