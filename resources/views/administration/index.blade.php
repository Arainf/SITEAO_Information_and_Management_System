<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Administration</h1>
            <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                Officer rosters by academic term
            </p>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-6">

        @forelse($terms as $term)
            <div class="sims-card overflow-hidden">
                {{-- Term header --}}
                <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid #e0e2e6;">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-semibold" style="color: #181d26; letter-spacing: 0.10px;">{{ $term->name }}</h2>
                            @if($term->is_active)
                                <span class="badge badge-active">Active</span>
                            @endif
                        </div>
                        <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">
                            {{ $term->start_date->format('M d, Y') }} – {{ $term->end_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                {{-- Officer roster --}}
                @if($term->officers->count())
                    <div class="px-5 py-4">
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($term->officers as $officer)
                                <div class="flex items-center gap-3 py-2 px-3 rounded-xl" style="background: #f5f6f8; border: 1px solid #e0e2e6;">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                         style="background-color: #1b61c9;">
                                        {{ strtoupper(substr($officer->user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium truncate" style="color: #181d26; letter-spacing: 0.08px;">{{ $officer->user->name }}</p>
                                        <p class="text-xs truncate" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">{{ $officer->position }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="px-5 py-6 text-center">
                        <p class="text-sm" style="color: rgba(4,14,32,0.4); letter-spacing: 0.08px;">No officers assigned for this term.</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="sims-card py-20 text-center">
                <p class="text-sm" style="color: rgba(4,14,32,0.5); letter-spacing: 0.08px;">No terms have been created yet.</p>
            </div>
        @endforelse

    </div>
</x-app-layout>
