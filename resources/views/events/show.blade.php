<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('events.index') }}"
                   class="w-8 h-8 rounded-lg flex items-center justify-center"
                   style="color: rgba(4,14,32,0.69); background: rgba(4,14,32,0.04);"
                   onmouseover="this.style.background='rgba(4,14,32,0.08)'"
                   onmouseout="this.style.background='rgba(4,14,32,0.04)'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">{{ $event->title }}</h1>
                        @if($event->isDraft())
                            <span class="badge badge-pending">Draft</span>
                        @elseif($event->isOpen())
                            <span class="badge badge-active">Open</span>
                        @else
                            <span class="badge badge-inactive">Closed</span>
                        @endif
                    </div>
                    <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                        By {{ $event->creator->name ?? 'Unknown' }}
                    </p>
                </div>
            </div>
            @if(auth()->user()->hasRole(['admin', 'moderator']))
                <a href="{{ route('events.edit', $event) }}" class="btn-secondary">Edit Event</a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-6">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <!-- Event Details -->
        <div class="sims-card p-6 space-y-4">
            <p class="text-sm leading-relaxed" style="color: #181d26;">{{ $event->description }}</p>

            <div class="flex flex-wrap gap-4 pt-3 border-t" style="border-color: #e0e2e6;">
                <div class="flex items-center gap-2 text-sm" style="color: rgba(4,14,32,0.69);">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $event->event_date->format('F d, Y \a\t g:i A') }}
                </div>
                @if($event->location)
                    <div class="flex items-center gap-2 text-sm" style="color: rgba(4,14,32,0.69);">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $event->location }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Facebook Post Embed -->
        @if($event->fb_post_url)
            <div class="sims-card p-6">
                <h2 class="text-sm font-semibold mb-4" style="color: #181d26; letter-spacing: 0.08px;">Official Facebook Post</h2>
                <div id="fb-root"></div>
                <div class="fb-post"
                     data-href="{{ $event->fb_post_url }}"
                     data-width="500"
                     data-show-text="true">
                </div>
            </div>
        @endif

        <!-- Participation Actions (non-admin/moderator) -->
        @if(! auth()->user()->hasRole(['admin', 'moderator']))
            <div class="sims-card p-6">
                <h2 class="text-sm font-semibold mb-4" style="color: #181d26; letter-spacing: 0.08px;">Your Participation</h2>

                @if(! $participation)
                    @if($event->isOpen())
                        <form method="POST" action="{{ route('events.join', $event) }}">
                            @csrf
                            <p class="text-sm mb-4" style="color: rgba(4,14,32,0.69);">You are not yet registered for this event.</p>
                            <button type="submit" class="btn-primary">Join Event</button>
                        </form>
                    @else
                        <p class="text-sm" style="color: rgba(4,14,32,0.55);">This event is not currently open for registration.</p>
                    @endif
                @else
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-sm" style="color: rgba(4,14,32,0.69);">Status:</span>
                        <span class="badge {{ $participation->statusBadgeClass() }}">{{ $participation->statusLabel() }}</span>
                    </div>

                    @if($participation->remarks)
                        <div class="mb-4 p-3 rounded-xl text-sm" style="background: rgba(215,43,43,0.06); border: 1px solid rgba(215,43,43,0.2); color: #d72b2b;">
                            <strong>Remarks:</strong> {{ $participation->remarks }}
                        </div>
                    @endif

                    @if($participation->isPendingProof() || $participation->isRejected())
                        <form method="POST" action="{{ route('events.proof', $event) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <p class="text-sm font-medium" style="color: #181d26;">Submit Proof of Participation</p>

                            <div>
                                <x-input-label for="proof_type" :value="__('Proof Type')" />
                                <select id="proof_type" name="proof_type" class="sims-input mt-1 block w-full" required>
                                    <option value="">Select type…</option>
                                    <option value="photo" @selected(old('proof_type') === 'photo')>Action Photo (JPEG/PNG)</option>
                                    <option value="certificate" @selected(old('proof_type') === 'certificate')>Certificate (JPEG/PNG/PDF)</option>
                                </select>
                                <x-input-error :messages="$errors->get('proof_type')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label for="proof_file" :value="__('Upload File')" />
                                <input id="proof_file" name="proof_file" type="file" required
                                    class="mt-1 block w-full text-sm rounded-xl border px-3 py-2"
                                    style="color: #181d26; border-color: #e0e2e6; background: #fff;">
                                <p class="text-xs mt-1" style="color: rgba(4,14,32,0.55);">Max 10 MB</p>
                                <x-input-error :messages="$errors->get('proof_file')" class="mt-1" />
                            </div>

                            <button type="submit" class="btn-primary">Submit Proof</button>
                        </form>
                    @elseif($participation->isSubmitted())
                        <p class="text-sm" style="color: rgba(4,14,32,0.69);">Your proof has been submitted and is awaiting review.</p>
                    @elseif($participation->isApproved())
                        <p class="text-sm" style="color: #2a9d5c;">Your participation has been approved.</p>
                        @if($participation->hasCert())
                            <a href="{{ route('events.certificate', $event) }}"
                               class="btn-primary mt-3 inline-flex text-sm">
                                Download Certificate
                            </a>
                        @elseif($event->cert_template)
                            <p class="text-xs mt-2" style="color: rgba(4,14,32,0.55);">Your certificate will be available once released by a moderator.</p>
                        @endif
                    @endif
                @endif
            </div>
        @endif

        <!-- Participants Table (admin/moderator) -->
        @if(auth()->user()->hasRole(['admin', 'moderator']))
            <div class="sims-card overflow-hidden">
                <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: #e0e2e6;">
                    <h2 class="text-sm font-semibold" style="color: #181d26; letter-spacing: 0.08px;">
                        Participants
                        <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs font-medium"
                              style="background: rgba(27,97,201,0.08); color: #1b61c9;">
                            {{ $event->participants->count() }}
                        </span>
                    </h2>
                </div>

                @if($event->participants->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm" style="color: rgba(4,14,32,0.55);">No participants yet.</p>
                    </div>
                @else
                    <table class="sims-table w-full">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Committee</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Proof</th>
                                <th>Certificate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($event->participants as $participant)
                                <tr>
                                    <td>
                                        <p class="font-medium" style="color: #181d26;">{{ $participant->name }}</p>
                                        <p class="text-xs" style="color: rgba(4,14,32,0.55);">{{ $participant->email }}</p>
                                    </td>
                                    <td>{{ $participant->committee ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $participant->pivot->statusBadgeClass() }}">
                                            {{ $participant->pivot->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-xs" style="color: rgba(4,14,32,0.69);">
                                        {{ $participant->pivot->joined_at?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td>
                                        @if($participant->pivot->proof_path)
                                            <a href="{{ Storage::url($participant->pivot->proof_path) }}"
                                               target="_blank"
                                               class="text-xs font-medium"
                                               style="color: #1b61c9;">View</a>
                                        @else
                                            <span class="text-xs" style="color: rgba(4,14,32,0.35);">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($participant->pivot->cert_released_at)
                                            <span class="badge badge-active">Released</span>
                                        @elseif($participant->pivot->isApproved())
                                            <span class="badge badge-pending">Pending release</span>
                                        @else
                                            <span class="text-xs" style="color: rgba(4,14,32,0.35);">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Certificate release form --}}
                    @if($event->cert_template)
                        @php
                            $releasable = $event->participants->filter(
                                fn($p) => $p->pivot->isApproved() && ! $p->pivot->cert_released_at
                            );
                        @endphp
                        @if($releasable->isNotEmpty())
                            <form method="POST" action="{{ route('events.certificates.release', $event) }}">
                                @csrf
                                <div class="px-6 py-5 border-t" style="border-color: #e0e2e6;">
                                    <p class="text-sm font-semibold mb-1" style="color: #181d26;">Release Certificates</p>
                                    <p class="text-xs mb-4" style="color: rgba(4,14,32,0.69);">Select approved participants to make their certificate available for download:</p>
                                    <div class="space-y-2 mb-4">
                                        @foreach($releasable as $participant)
                                            <label class="flex items-center gap-2.5 text-sm cursor-pointer">
                                                <input type="checkbox" name="user_ids[]" value="{{ $participant->id }}"
                                                       class="rounded"
                                                       style="accent-color: #1b61c9; width: 15px; height: 15px;">
                                                <span style="color: #181d26;">{{ $participant->name }}</span>
                                                @if($participant->committee)
                                                    <span class="text-xs" style="color: rgba(4,14,32,0.55);">· {{ $participant->committee }}</span>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn-primary text-sm">Release Selected</button>
                                </div>
                            </form>
                        @elseif($event->participants->where('pivot.status', 'approved')->isNotEmpty())
                            <div class="px-6 py-4 border-t" style="border-color: #e0e2e6;">
                                <p class="text-sm" style="color: rgba(4,14,32,0.55);">All approved participants have already received their certificates.</p>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        @endif

    </div>

    @if($event->fb_post_url)
        @push('scripts')
        <script async defer crossorigin="anonymous"
            src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0">
        </script>
        @endpush
    @endif
</x-app-layout>
