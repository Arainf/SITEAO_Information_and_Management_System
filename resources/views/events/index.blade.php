<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Events</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Browse and join organisation events
                </p>
            </div>
            @if(auth()->user()->hasRole(['admin', 'moderator']))
                <a href="{{ route('events.create') }}" class="btn-primary">
                    + Create Event
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if($events->isEmpty())
            <div class="sims-card p-12 text-center">
                <div class="w-12 h-12 mx-auto rounded-2xl flex items-center justify-center text-2xl mb-4"
                     style="background: rgba(27,97,201,0.08);">📅</div>
                <p class="font-semibold text-sm" style="color: #181d26;">No events yet</p>
                <p class="text-xs mt-1" style="color: rgba(4,14,32,0.69);">Check back later for upcoming events.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($events as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="sims-card p-6 flex flex-col gap-3 no-underline block"
                       onmouseover="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.12) 0px 2px 6px, rgba(45,127,249,0.36) 0px 2px 8px'"
                       onmouseout="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.08) 0px 0px 2px, rgba(45,127,249,0.28) 0px 1px 3px, rgba(0,0,0,0.06) 0px 0px 0px 0.5px inset'">

                        <div class="flex items-start justify-between gap-2">
                            <h2 class="font-semibold text-sm leading-snug flex-1" style="color: #181d26; letter-spacing: 0.08px;">
                                {{ $event->title }}
                            </h2>
                            @if($event->isDraft())
                                <span class="badge badge-pending shrink-0">Draft</span>
                            @elseif($event->isOpen())
                                <span class="badge badge-active shrink-0">Open</span>
                            @else
                                <span class="badge badge-inactive shrink-0">Closed</span>
                            @endif
                        </div>

                        <p class="text-xs line-clamp-2" style="color: rgba(4,14,32,0.69);">
                            {{ $event->description }}
                        </p>

                        <div class="mt-auto pt-2 border-t flex flex-col gap-1" style="border-color: #e0e2e6;">
                            <div class="flex items-center gap-1.5 text-xs" style="color: rgba(4,14,32,0.69);">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $event->event_date->format('M d, Y') }}
                            </div>
                            @if($event->location)
                                <div class="flex items-center gap-1.5 text-xs" style="color: rgba(4,14,32,0.69);">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $event->location }}
                                </div>
                            @endif
                            <div class="flex items-center gap-1.5 text-xs" style="color: rgba(4,14,32,0.69);">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                By {{ $event->creator->name ?? 'Unknown' }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div>
                {{ $events->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
