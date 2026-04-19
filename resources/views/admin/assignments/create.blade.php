<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.assignments.index') }}" class="text-sm hover:underline" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">Assignments</a>
                <span style="color: rgba(4,14,32,0.25);">/</span>
            @endif
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Assign Position</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    @if(auth()->user()->isAdmin())
                        Assignment will be active immediately.
                    @else
                        Your request will be sent to an admin for approval.
                    @endif
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-xl mx-auto">
        <div class="sims-card p-6 space-y-5">

            @if(! auth()->user()->isAdmin())
                <div class="flex items-start gap-3 py-3 px-4 rounded-xl" style="background: #fffbeb; border: 1px solid #fde68a;">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #92400e;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm" style="color: #92400e; letter-spacing: 0.08px;">
                        As a moderator, your assignment request will be reviewed by an admin before it becomes active.
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.assignments.store') }}" class="space-y-5"
                  x-data="assignForm()">
                @csrf

                {{-- User search --}}
                <div>
                    <x-input-label for="user_search" :value="__('Member')" />
                    <div class="relative mt-1">
                        <input type="text" id="user_search" x-model="userQuery"
                            @input.debounce.300ms="searchUsers()"
                            @focus="userOpen = true" @click.outside="userOpen = false"
                            placeholder="Search by name or email…"
                            class="sims-input block w-full" autocomplete="off"
                            :class="selectedUserId ? 'border-green-400' : ''" />
                        <input type="hidden" name="user_id" x-model="selectedUserId" required />
                        <div x-show="userOpen && userResults.length > 0"
                             class="absolute z-20 mt-1 w-full rounded-xl shadow-lg overflow-hidden"
                             style="background: #fff; border: 1px solid #e0e2e6;">
                            <template x-for="u in userResults" :key="u.id">
                                <button type="button" @click="selectUser(u)"
                                        class="w-full text-left px-4 py-2.5 hover:bg-gray-50 transition-colors flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                         style="background: #1b61c9;" x-text="u.name.charAt(0).toUpperCase()"></div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium truncate" style="color: #181d26;" x-text="u.name"></p>
                                        <p class="text-xs truncate" style="color: rgba(4,14,32,0.55);" x-text="u.email"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                </div>

                {{-- Position select --}}
                <div>
                    <x-input-label for="position_id" :value="__('Position')" />
                    <select id="position_id" name="position_id" class="sims-input mt-1 block w-full" required
                            x-model="selectedPositionId">
                        <option value="">— Select a position —</option>
                        @php $grouped = $positions->groupBy(fn($p) => $p->committee?->name ?? 'General'); @endphp
                        @foreach($grouped as $group => $groupPositions)
                            <optgroup label="{{ $group }}">
                                @foreach($groupPositions as $position)
                                    <option value="{{ $position->id }}" @selected(old('position_id') == $position->id)>
                                        {{ $position->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('position_id')" class="mt-1" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary"
                            :disabled="!selectedUserId || !selectedPositionId">
                        @if(auth()->user()->isAdmin())
                            Assign Position
                        @else
                            Submit for Approval
                        @endif
                    </button>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.assignments.index') }}" class="btn-secondary">Cancel</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-secondary">Cancel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    function assignForm() {
        return {
            userQuery: '{{ $selectedUser ? $selectedUser->name . " (" . $selectedUser->email . ")" : "" }}',
            userResults: [],
            userOpen: false,
            selectedUserId: '{{ $selectedUser?->id ?? "" }}',
            selectedPositionId: '{{ old("position_id", "") }}',
            async searchUsers() {
                if (this.userQuery.length < 2) { this.userResults = []; return; }
                const res = await fetch(`/api/users/search?q=${encodeURIComponent(this.userQuery)}`);
                this.userResults = await res.json();
                this.userOpen = true;
            },
            selectUser(u) {
                this.userQuery     = u.name + ' (' + u.email + ')';
                this.selectedUserId = u.id;
                this.userOpen      = false;
                this.userResults   = [];
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
