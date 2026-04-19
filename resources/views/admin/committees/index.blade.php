<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Committees</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Manage committees and their positions
                </p>
            </div>
            <a href="{{ route('admin.positions.index') }}" class="btn-secondary" style="font-size: 13px;">
                Manage Positions
            </a>
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

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Add committee form --}}
            <div class="sims-card p-5">
                <h2 class="text-sm font-semibold mb-4" style="color: #181d26; letter-spacing: 0.10px;">New Committee</h2>
                <form method="POST" action="{{ route('admin.committees.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name')" placeholder="e.g. Academic Committee" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="description" :value="__('Description (optional)')" />
                        <textarea id="description" name="description" rows="2"
                            class="sims-input mt-1 block w-full resize-none text-sm"
                            placeholder="Brief description…">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>
                    <button type="submit" class="btn-primary">Create Committee</button>
                </form>
            </div>

            {{-- Committee list --}}
            <div class="space-y-3">
                @forelse($committees as $committee)
                    <div class="sims-card p-4" x-data="{ editing: false }">
                        <div x-show="!editing" class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold" style="color: #181d26; letter-spacing: 0.09px;">
                                    {{ $committee->name }}
                                </p>
                                @if($committee->description)
                                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">
                                        {{ $committee->description }}
                                    </p>
                                @endif
                                <p class="text-xs mt-1" style="color: rgba(4,14,32,0.4); letter-spacing: 0.07px;">
                                    {{ $committee->positions_count }} {{ Str::plural('position', $committee->positions_count) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button @click="editing = true"
                                        class="text-xs font-medium hover:underline" style="color: #1b61c9; letter-spacing: 0.07px;">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.committees.destroy', $committee) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium hover:underline" style="color: #991b1b; letter-spacing: 0.07px;"
                                            onclick="return confirm('Delete committee {{ addslashes($committee->name) }}?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <form x-show="editing" method="POST" action="{{ route('admin.committees.update', $committee) }}"
                              class="space-y-3" x-cloak>
                            @csrf
                            @method('PUT')
                            <div>
                                <x-text-input name="name" type="text" class="block w-full text-sm"
                                    value="{{ $committee->name }}" required />
                            </div>
                            <div>
                                <textarea name="description" rows="2"
                                    class="sims-input block w-full resize-none text-sm">{{ $committee->description }}</textarea>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="btn-primary" style="font-size: 12px; padding: 5px 12px;">Save</button>
                                <button type="button" @click="editing = false"
                                        class="btn-secondary" style="font-size: 12px; padding: 5px 12px;">Cancel</button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="sims-card py-12 text-center">
                        <p class="text-sm" style="color: rgba(4,14,32,0.5); letter-spacing: 0.08px;">No committees yet.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
