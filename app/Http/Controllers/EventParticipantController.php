<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitProofRequest;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventParticipantController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if (! $event->isOpen()) {
            return back()->with('error', 'This event is not open for joining.');
        }

        $user = $request->user();

        if ($event->hasParticipant($user)) {
            return back()->with('error', 'You are already registered for this event.');
        }

        $event->eventParticipants()->create([
            'user_id'   => $user->id,
            'status'    => EventParticipant::STATUS_PENDING_PROOF,
            'joined_at' => now(),
        ]);

        return back()->with('success', 'You have joined the event.');
    }

    public function submitProof(SubmitProofRequest $request, Event $event)
    {
        $user          = $request->user();
        $participation = $event->participantRecord($user);

        if (! $participation || $participation->isApproved()) {
            return back()->with('error', 'Cannot submit proof at this time.');
        }

        if ($participation->proof_path) {
            Storage::disk('public')->delete($participation->proof_path);
        }

        $file = $request->file('proof_file');
        $ext  = $file->getClientOriginalExtension();
        $path = $file->storeAs("proofs/{$event->id}/{$user->id}", "proof.{$ext}", 'public');

        $participation->update([
            'proof_type'   => $request->input('proof_type'),
            'proof_path'   => $path,
            'status'       => EventParticipant::STATUS_SUBMITTED,
            'submitted_at' => now(),
            'remarks'      => null,
        ]);

        return back()->with('success', 'Proof submitted successfully.');
    }
}
