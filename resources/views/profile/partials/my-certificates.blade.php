<section class="p-6">
    <header class="mb-4">
        <h2 class="text-sm font-semibold" style="color: #181d26; letter-spacing: 0.08px;">My Certificates</h2>
        <p class="mt-1 text-xs" style="color: rgba(4,14,32,0.69);">Download your certificates for approved participations.</p>
    </header>

    @php
        $certs = \App\Models\EventParticipant::with('event')
            ->where('user_id', auth()->id())
            ->whereNotNull('cert_released_at')
            ->latest('cert_released_at')
            ->get();
    @endphp

    @if($certs->isEmpty())
        <p class="text-sm" style="color: rgba(4,14,32,0.55);">No certificates available yet.</p>
    @else
        <div class="space-y-0">
            @foreach($certs as $cert)
                <div class="flex items-center justify-between gap-4 py-3 border-b last:border-0" style="border-color: #e0e2e6;">
                    <div class="min-w-0">
                        <p class="text-sm font-medium truncate" style="color: #181d26;">{{ $cert->event->title }}</p>
                        <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.55);">
                            {{ $cert->event->event_date->format('F d, Y') }}
                            &nbsp;·&nbsp;
                            Released {{ $cert->cert_released_at->format('M d, Y') }}
                        </p>
                    </div>
                    <a href="{{ route('events.certificate', $cert->event_id) }}"
                       class="btn-outline text-xs px-4 py-2 no-underline shrink-0"
                       style="border-radius: 10px;">
                        Download
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</section>
