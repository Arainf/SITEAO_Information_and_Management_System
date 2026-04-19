<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.terms.index') }}" class="text-sm hover:underline" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">Terms</a>
                <span style="color: rgba(4,14,32,0.25);">/</span>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">{{ $term->name }}</h1>
                        @if($term->is_active)
                            <span class="badge badge-active">Active</span>
                        @endif
                    </div>
                    <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">
                        {{ $term->start_date->format('M d, Y') }} – {{ $term->end_date->format('M d, Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(! $term->is_active)
                    <form method="POST" action="{{ route('admin.terms.activate', $term) }}">
                        @csrf
                        <button type="submit" class="btn-primary" style="font-size: 13px; padding: 6px 14px;">Activate</button>
                    </form>
                @endif
                <a href="{{ route('admin.terms.edit', $term) }}" class="btn-secondary" style="font-size: 13px; padding: 6px 14px;">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">

        @if(session('success'))
            <div class="alert-success flex items-start gap-3">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #166534;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-error flex items-start gap-3">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #991b1b;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Officer Roster --}}
        <div class="sims-card overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid #e0e2e6;">
                <h2 class="text-sm font-semibold" style="color: #181d26; letter-spacing: 0.10px;">Officer Roster</h2>
                <span class="text-xs" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">{{ $term->officers->count() }} {{ Str::plural('officer', $term->officers->count()) }}</span>
            </div>

            @if($term->officers->count())
                <table class="min-w-full sims-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($term->officers as $officer)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                             style="background-color: #1b61c9;">
                                            {{ strtoupper(substr($officer->user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium" style="color: #181d26; letter-spacing: 0.08px;">{{ $officer->user->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-xs" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">{{ $officer->user->email }}</span>
                                </td>
                                <td>
                                    <span class="text-sm" style="color: #181d26; letter-spacing: 0.08px;">{{ $officer->position }}</span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.terms.officers.destroy', [$term, $officer]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium hover:underline"
                                                style="color: #991b1b; letter-spacing: 0.07px;"
                                                onclick="return confirm('Remove {{ addslashes($officer->user->name) }} from this term?')">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-10 text-center">
                    <p class="text-sm" style="color: rgba(4,14,32,0.5); letter-spacing: 0.08px;">No officers assigned yet.</p>
                </div>
            @endif

            {{-- Add officer form --}}
            <div class="px-5 py-4" style="border-top: 1px solid #e0e2e6; background: #f5f6f8;">
                <p class="text-xs font-semibold mb-3" style="color: rgba(4,14,32,0.69); letter-spacing: 0.09px; text-transform: uppercase;">Add Officer</p>
                <form method="POST" action="{{ route('admin.terms.officers.store', $term) }}"
                      x-data="tagSearch()" class="flex flex-wrap gap-3 items-end">
                    @csrf

                    <div class="flex-1 min-w-52">
                        <label class="block text-xs font-medium mb-1" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">User</label>
                        <div class="relative">
                            <input type="text" x-model="query" @input.debounce.300ms="search()"
                                @focus="open = true" @click.outside="open = false"
                                placeholder="Search name or email…"
                                class="sims-input w-full text-sm" autocomplete="off" />
                            <input type="hidden" name="user_id" x-model="selectedId" required />
                            <div x-show="open && results.length > 0"
                                 class="absolute z-20 mt-1 w-full rounded-xl shadow-lg overflow-hidden"
                                 style="background: #fff; border: 1px solid #e0e2e6;">
                                <template x-for="u in results" :key="u.id">
                                    <button type="button"
                                            @click="select(u)"
                                            class="w-full text-left px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                        <p class="text-sm font-medium" style="color: #181d26;" x-text="u.name"></p>
                                        <p class="text-xs" style="color: rgba(4,14,32,0.55);" x-text="u.email"></p>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 min-w-40">
                        <label for="position" class="block text-xs font-medium mb-1" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Position</label>
                        <input type="text" id="position" name="position" placeholder="e.g. President"
                               class="sims-input w-full text-sm" required />
                    </div>

                    <button type="submit" class="btn-primary" style="font-size: 13px; padding: 8px 16px;">Add</button>
                </form>
            </div>
        </div>

        {{-- Linked Events --}}
        <div class="sims-card overflow-hidden">
            <div class="px-5 py-4" style="border-bottom: 1px solid #e0e2e6;">
                <h2 class="text-sm font-semibold" style="color: #181d26; letter-spacing: 0.10px;">Events</h2>
            </div>

            @if($term->events->count())
                <table class="min-w-full sims-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($term->events->sortByDesc('event_date') as $event)
                            <tr>
                                <td>
                                    <a href="{{ route('events.show', $event) }}"
                                       class="text-sm font-medium hover:underline" style="color: #1b61c9; letter-spacing: 0.08px;">
                                        {{ $event->title }}
                                    </a>
                                </td>
                                <td>
                                    <span class="text-xs" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">
                                        {{ $event->event_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-10 text-center">
                    <p class="text-sm" style="color: rgba(4,14,32,0.5); letter-spacing: 0.08px;">No events linked to this term.</p>
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
    <script>
    function tagSearch() {
        return {
            query: '',
            results: [],
            open: false,
            selectedId: null,
            async search() {
                if (this.query.length < 2) { this.results = []; return; }
                const res = await fetch(`/api/users/search?q=${encodeURIComponent(this.query)}`);
                this.results = await res.json();
                this.open = true;
            },
            select(u) {
                this.query = u.name + ' (' + u.email + ')';
                this.selectedId = u.id;
                this.open = false;
                this.results = [];
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
