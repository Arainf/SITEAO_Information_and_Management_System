<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Event::with('creator');

        if (! $user->hasRole(['admin', 'moderator'])) {
            $query->visible();
        }

        $events = $query->latest('event_date')->paginate(15);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $committees = User::select('committee')
            ->distinct()
            ->whereNotNull('committee')
            ->pluck('committee');

        return view('events.create', compact('committees'));
    }

    public function store(StoreEventRequest $request)
    {
        $event = Event::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        $this->processTagging($event, $request->input('tagged_users', []));

        return redirect()->route('events.show', $event)->with('success', 'Event created.');
    }

    public function show(Event $event, Request $request)
    {
        $user = $request->user();

        if ($event->isDraft() && ! $user->hasRole(['admin', 'moderator'])) {
            abort(403);
        }

        $event->load('creator', 'participants');
        $participation = $event->participantRecord($user);

        return view('events.show', compact('event', 'participation'));
    }

    public function edit(Event $event)
    {
        $committees  = User::select('committee')
            ->distinct()
            ->whereNotNull('committee')
            ->pluck('committee');

        $taggedUsers = $event->participants()->get(['users.id', 'users.name', 'users.email']);

        return view('events.edit', compact('event', 'committees', 'taggedUsers'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());
        $this->processTagging($event, $request->input('tagged_users', []));

        return redirect()->route('events.show', $event)->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted.');
    }

    private function processTagging(Event $event, array $userIds): void
    {
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (! $user || ! $user->canAccess()) {
                continue;
            }
            $event->eventParticipants()->firstOrCreate(
                ['user_id' => $userId],
                ['status' => 'pending_proof', 'joined_at' => now()]
            );
        }
    }
}
