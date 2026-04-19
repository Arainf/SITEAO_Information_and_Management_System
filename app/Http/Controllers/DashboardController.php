<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'moderator') {
            $myEvents       = Event::where('created_by', $user->id)->count();
            $myParticipants = EventParticipant::whereHas('event', fn($q) => $q->where('created_by', $user->id))->count();
            $pendingCount   = EventParticipant::where('status', EventParticipant::STATUS_SUBMITTED)->count();

            return view('dashboard.moderator', compact('myEvents', 'myParticipants', 'pendingCount'));
        }

        return match ($user->role) {
            'admin'   => view('dashboard.admin'),
            'officer' => view('dashboard.officer'),
            'member'  => view('dashboard.member'),
            default   => view('dashboard.default'),
        };
    }
}
