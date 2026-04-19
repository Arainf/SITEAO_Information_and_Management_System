<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Validation Queue</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Review and approve proof submissions
                </p>
            </div>
            @if($submissions->total() > 0)
                <span class="px-3 py-1 rounded-full text-sm font-medium"
                      style="background: rgba(27,97,201,0.08); color: #1b61c9; letter-spacing: 0.07px;">
                    {{ $submissions->total() }} pending
                </span>
            @endif
        </div>
    </x-slot>

    <div x-data="{
            rejectOpen: false,
            rejectEventId: null,
            rejectUserId: null,
            remarks: '',
            openReject(eventId, userId) {
                this.rejectEventId = eventId;
                this.rejectUserId = userId;
                this.remarks = '';
                this.rejectOpen = true;
            }
         }"
         class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <!-- Reject modal -->
        <div x-show="rejectOpen"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background: rgba(4,14,32,0.4);"
             @click.self="rejectOpen = false">

            <div x-show="rejectOpen"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="sims-card p-6 w-full max-w-md mx-4 space-y-4">

                <h2 class="text-sm font-semibold" style="color: #181d26; letter-spacing: 0.08px;">Reject Participation</h2>
                <p class="text-sm" style="color: rgba(4,14,32,0.69);">Provide a reason for rejection. The participant will see this message.</p>

                <form method="POST"
                      :action="`/validation/${rejectEventId}/${rejectUserId}/reject`"
                      class="space-y-4">
                    @csrf
                    <div>
                        <textarea name="remarks"
                                  x-model="remarks"
                                  rows="4"
                                  maxlength="500"
                                  placeholder="Enter rejection reason…"
                                  required
                                  class="sims-input block w-full resize-none"
                                  style="font-size: 0.875rem;"></textarea>
                    </div>
                    <div class="flex items-center gap-3 justify-end">
                        <button type="button"
                                @click="rejectOpen = false"
                                class="btn-outline text-sm px-4 py-2">
                            Cancel
                        </button>
                        <button type="submit"
                                class="text-sm font-medium px-4 py-2 rounded-xl transition-all duration-150"
                                style="background: #d72b2b; color: #fff; border: none;">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($submissions->isEmpty())
            <div class="sims-card p-10 text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl mx-auto mb-3"
                     style="background: rgba(42,157,92,0.08);">
                    ✅
                </div>
                <p class="text-sm font-medium" style="color: #181d26;">All caught up</p>
                <p class="text-xs mt-1" style="color: rgba(4,14,32,0.55);">No proof submissions are awaiting review.</p>
            </div>
        @else
            <div class="sims-card overflow-hidden">
                <table class="sims-table w-full">
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>Committee</th>
                            <th>Event</th>
                            <th>Submitted</th>
                            <th>Type</th>
                            <th>Proof</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $s)
                            <tr>
                                <td>
                                    <p class="font-medium" style="color: #181d26;">{{ $s->user->name }}</p>
                                    <p class="text-xs" style="color: rgba(4,14,32,0.55);">{{ $s->user->email }}</p>
                                </td>
                                <td>{{ $s->user->committee ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('events.show', $s->event_id) }}"
                                       class="text-sm font-medium no-underline"
                                       style="color: #1b61c9;">
                                        {{ $s->event->title }}
                                    </a>
                                </td>
                                <td class="text-xs" style="color: rgba(4,14,32,0.69);">
                                    {{ $s->submitted_at?->format('M d, Y g:i A') ?? '—' }}
                                </td>
                                <td>
                                    <span class="badge badge-pending" style="text-transform: capitalize;">
                                        {{ $s->proof_type ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    @if($s->proof_path)
                                        <a href="{{ Storage::url($s->proof_path) }}"
                                           target="_blank"
                                           class="text-xs font-medium"
                                           style="color: #1b61c9;">View</a>
                                    @else
                                        <span class="text-xs" style="color: rgba(4,14,32,0.35);">None</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <!-- Approve -->
                                        <form method="POST"
                                              action="{{ route('validation.approve', [$s->event_id, $s->user_id]) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs font-medium px-3 py-1.5 rounded-lg transition-all duration-150"
                                                    style="background: rgba(42,157,92,0.1); color: #2a9d5c; border: none;">
                                                Approve
                                            </button>
                                        </form>

                                        <!-- Reject -->
                                        <button type="button"
                                                @click="openReject({{ $s->event_id }}, {{ $s->user_id }})"
                                                class="text-xs font-medium px-3 py-1.5 rounded-lg transition-all duration-150"
                                                style="background: rgba(215,43,43,0.08); color: #d72b2b; border: none;">
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
