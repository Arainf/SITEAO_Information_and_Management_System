<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Events</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Organisation activity feed
                </p>
            </div>
            @if(auth()->user()->hasRole(['admin', 'moderator']))
                <a href="{{ route('events.create') }}" class="btn-primary">+ Create Event</a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto space-y-4">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if($feed->isEmpty())
            <div class="sims-card p-12 text-center">
                <div class="w-12 h-12 mx-auto rounded-2xl flex items-center justify-center text-2xl mb-4"
                     style="background: rgba(27,97,201,0.08);">📅</div>
                <p class="font-semibold text-sm" style="color: #181d26;">Nothing here yet</p>
                <p class="text-xs mt-1" style="color: rgba(4,14,32,0.69);">Events will appear here once they're created.</p>
            </div>
        @else
            @foreach($feed as $item)

                {{-- Event post --}}
                @if($item['type'] === 'event')
                    @php $event = $item['event']; @endphp
                    <div class="sims-card p-5 flex gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 mt-0.5"
                             style="background: rgba(27,97,201,0.08);">
                            📅
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <a href="{{ route('events.show', $event) }}"
                                   class="font-semibold text-sm leading-snug no-underline hover:underline"
                                   style="color: #181d26;">
                                    {{ $event->title }}
                                </a>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    @if($event->isDraft())
                                        <span class="badge badge-pending">Draft</span>
                                    @elseif($event->isOpen())
                                        <span class="badge badge-active">Open</span>
                                    @else
                                        <span class="badge badge-inactive">Closed</span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-sm line-clamp-2 mb-3" style="color: rgba(4,14,32,0.69);">
                                {{ $event->description }}
                            </p>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs" style="color: rgba(4,14,32,0.55);">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $event->event_date->format('M d, Y') }}
                                </span>
                                @if($event->location)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $event->location }}
                                    </span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    By {{ $event->creator->name ?? 'Unknown' }}
                                </span>
                                @if($event->eventParticipants->count() > 0)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $event->eventParticipants->count() }} participant{{ $event->eventParticipants->count() === 1 ? '' : 's' }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('events.show', $event) }}"
                                   class="text-xs font-medium no-underline"
                                   style="color: #1b61c9;">
                                    View event →
                                </a>
                            </div>
                        </div>
                    </div>

                {{-- Join activity (privileged only) --}}
                @elseif($item['type'] === 'join' && $isPrivileged)
                    <div class="flex gap-3 px-2 py-1 items-start">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shrink-0 mt-0.5"
                             style="background-color: #1b61c9;">
                            {{ strtoupper(substr($item['user']->name ?? '?', 0, 1)) }}
                        </div>

                        <div class="flex-1 min-w-0 pt-1">
                            <p class="text-sm" style="color: rgba(4,14,32,0.69);">
                                <span class="font-medium" style="color: #181d26;">{{ $item['user']->name ?? 'Unknown' }}</span>
                                joined
                                <a href="{{ route('events.show', $item['event']) }}"
                                   class="font-medium no-underline hover:underline"
                                   style="color: #1b61c9;">{{ $item['event']->title ?? 'an event' }}</a>
                            </p>
                            <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.35);">
                                {{ $item['date']->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endif

            @endforeach
        @endif

    </div>
</x-app-layout>
