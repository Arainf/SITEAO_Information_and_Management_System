<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function index()
    {
        $submissions = EventParticipant::with(['event', 'user'])
            ->where('status', EventParticipant::STATUS_SUBMITTED)
            ->orderBy('submitted_at')
            ->paginate(20);

        return view('validation.index', compact('submissions'));
    }

    public function approve(Request $request, $eventId, $userId)
    {
        EventParticipant::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->firstOrFail();

        EventParticipant::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->update([
                'status'  => EventParticipant::STATUS_APPROVED,
                'remarks' => null,
            ]);

        return back()->with('success', 'Participation approved.');
    }

    public function reject(Request $request, $eventId, $userId)
    {
        $request->validate(['remarks' => ['required', 'string', 'max:500']]);

        EventParticipant::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->firstOrFail();

        EventParticipant::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->update([
                'status'  => EventParticipant::STATUS_REJECTED,
                'remarks' => $request->remarks,
            ]);

        return back()->with('success', 'Participation rejected.');
    }
}
