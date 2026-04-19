<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\User;
use App\Models\UserPosition;
use Illuminate\Http\Request;

class UserPositionController extends Controller
{
    // Assignment approval queue — admin only
    public function index()
    {
        $pending = UserPosition::with(['user', 'position.committee', 'assignedBy'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.assignments.index', compact('pending'));
    }

    // Assign form — admin + moderator
    public function create(Request $request)
    {
        $positions  = Position::with('committee')->orderBy('name')->get();
        $users      = User::where('status', 'active')
            ->whereNotIn('role', ['pending'])
            ->orderBy('name')
            ->get();

        $selectedUser = $request->query('user_id') ? User::find($request->query('user_id')) : null;

        return view('admin.assignments.create', compact('positions', 'users', 'selectedUser'));
    }

    // Store assignment — admin saves as active, mod saves as pending
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => ['required', 'exists:users,id'],
            'position_id' => ['required', 'exists:positions,id'],
        ]);

        $actor  = $request->user();
        $status = $actor->isAdmin() ? 'active' : 'pending';

        $existing = UserPosition::where('user_id', $request->user_id)
            ->where('position_id', $request->position_id)
            ->first();

        if ($existing) {
            if ($existing->isActive()) {
                return back()->with('error', 'This user already holds this position.');
            }
            if ($existing->isPending()) {
                return back()->with('error', 'An assignment request for this position is already pending approval.');
            }
            // rejected — allow re-assignment
            $existing->update(['status' => $status, 'assigned_by' => $actor->id, 'remarks' => null]);
        } else {
            UserPosition::create([
                'user_id'     => $request->user_id,
                'position_id' => $request->position_id,
                'assigned_by' => $actor->id,
                'status'      => $status,
            ]);
        }

        $msg = $actor->isAdmin()
            ? 'Position assigned successfully.'
            : 'Assignment request submitted for admin approval.';

        return redirect()->route('admin.assignments.index')->with('success', $msg);
    }

    // Approve pending assignment — admin only
    public function approve(UserPosition $assignment)
    {
        $assignment->update(['status' => 'active', 'remarks' => null]);
        return back()->with('success', 'Assignment approved.');
    }

    // Reject pending assignment — admin only
    public function reject(Request $request, UserPosition $assignment)
    {
        $request->validate(['remarks' => ['nullable', 'string', 'max:500']]);
        $assignment->update(['status' => 'rejected', 'remarks' => $request->remarks]);
        return back()->with('success', 'Assignment rejected.');
    }

    // Remove an active assignment — admin only
    public function destroy(UserPosition $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Assignment removed.');
    }
}
