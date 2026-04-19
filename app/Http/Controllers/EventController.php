<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user         = $request->user();
        $isPrivileged = $user->hasRole(['admin', 'moderator', 'officer']);

        $eventQuery = Event::with('creator', 'eventParticipants');
        if (! $user->hasRole(['admin', 'moderator'])) {
            $eventQuery->visible();
        }
        $events = $eventQuery->latest('event_date')->get();

        $joinActivity = collect();
        if ($isPrivileged) {
            $joinActivity = EventParticipant::with(['user', 'event'])
                ->whereNotNull('joined_at')
                ->latest('joined_at')
                ->limit(50)
                ->get()
                ->map(fn($ep) => [
                    'type'  => 'join',
                    'date'  => $ep->joined_at,
                    'user'  => $ep->user,
                    'event' => $ep->event,
                ]);
        }

        $eventItems = $events->map(fn($e) => [
            'type'  => 'event',
            'date'  => $e->created_at,
            'event' => $e,
        ]);

        $feed = $eventItems->concat($joinActivity)
            ->sortByDesc('date')
            ->values();

        return view('events.index', compact('feed', 'user', 'isPrivileged'));
    }

    public function create()
    {
        $committees = User::select('committee')
            ->distinct()
            ->whereNotNull('committee')
            ->pluck('committee');

        $terms      = Term::orderByDesc('start_date')->get();
        $activeTerm = Term::active();

        return view('events.create', compact('committees', 'terms', 'activeTerm'));
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();
        unset($validated['cert_template']);

        $event = Event::create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        if ($request->hasFile('cert_template')) {
            $path = $request->file('cert_template')
                ->storeAs('cert_templates', $event->id . '.png', 'public');
            $event->update(['cert_template' => $path]);
        }

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
        $terms       = Term::orderByDesc('start_date')->get();
        $activeTerm  = Term::active();

        return view('events.edit', compact('event', 'committees', 'taggedUsers', 'terms', 'activeTerm'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $validated = $request->validated();
        unset($validated['cert_template']);

        if ($request->hasFile('cert_template')) {
            if ($event->cert_template) {
                Storage::disk('public')->delete($event->cert_template);
            }
            $validated['cert_template'] = $request->file('cert_template')
                ->storeAs('cert_templates', $event->id . '.png', 'public');
        }

        $event->update($validated);
        $this->processTagging($event, $request->input('tagged_users', []));

        return redirect()->route('events.show', $event)->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        if ($event->cert_template) {
            Storage::disk('public')->delete($event->cert_template);
        }
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
