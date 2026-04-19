<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Terms</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Manage academic year terms and officer rosters
                </p>
            </div>
            <a href="{{ route('admin.terms.create') }}" class="btn-primary">New Term</a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-5">

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

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($terms as $term)
                <div class="sims-card p-5 flex flex-col gap-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold truncate" style="color: #181d26; letter-spacing: 0.10px;">
                                {{ $term->name }}
                            </p>
                            <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">
                                {{ $term->start_date->format('M d, Y') }} – {{ $term->end_date->format('M d, Y') }}
                            </p>
                        </div>
                        @if($term->is_active)
                            <span class="badge badge-active flex-shrink-0">Active</span>
                        @endif
                    </div>

                    <div class="flex gap-4 text-xs" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">
                        <span>{{ $term->officers_count }} {{ Str::plural('officer', $term->officers_count) }}</span>
                        <span>{{ $term->events_count }} {{ Str::plural('event', $term->events_count) }}</span>
                    </div>

                    <div class="flex flex-wrap gap-2 pt-1" style="border-top: 1px solid #e0e2e6;">
                        <a href="{{ route('admin.terms.show', $term) }}" class="btn-secondary" style="font-size: 12px; padding: 5px 12px;">
                            View
                        </a>
                        <a href="{{ route('admin.terms.edit', $term) }}" class="btn-secondary" style="font-size: 12px; padding: 5px 12px;">
                            Edit
                        </a>
                        @if(! $term->is_active)
                            <form method="POST" action="{{ route('admin.terms.activate', $term) }}">
                                @csrf
                                <button type="submit" class="btn-primary" style="font-size: 12px; padding: 5px 12px;">
                                    Activate
                                </button>
                            </form>
                        @endif
                        @if($term->events_count === 0)
                            <form method="POST" action="{{ route('admin.terms.destroy', $term) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="font-size: 12px; padding: 5px 12px;"
                                        onclick="return confirm('Delete term {{ addslashes($term->name) }}?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full sims-card py-16 text-center">
                    <p class="text-sm" style="color: rgba(4,14,32,0.5); letter-spacing: 0.08px;">No terms yet. Create the first one.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
