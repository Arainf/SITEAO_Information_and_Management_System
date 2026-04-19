<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.terms.index') }}" class="text-sm hover:underline" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">Terms</a>
            <span style="color: rgba(4,14,32,0.25);">/</span>
            <a href="{{ route('admin.terms.show', $term) }}" class="text-sm hover:underline" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">{{ $term->name }}</a>
            <span style="color: rgba(4,14,32,0.25);">/</span>
            <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Edit</h1>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto">
        <div class="sims-card p-6 space-y-5">
            <form method="POST" action="{{ route('admin.terms.update', $term) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" :value="__('Term Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        :value="old('name', $term->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="start_date" :value="__('Start Date')" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                            :value="old('start_date', $term->start_date->format('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="end_date" :value="__('End Date')" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                            :value="old('end_date', $term->end_date->format('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-1" />
                    </div>
                </div>

                <div class="flex items-center gap-3 py-3 px-4 rounded-xl" style="background: #f5f6f8; border: 1px solid #e0e2e6;">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $term->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                    <div>
                        <label for="is_active" class="text-sm font-medium cursor-pointer" style="color: #181d26; letter-spacing: 0.08px;">
                            Active term
                        </label>
                        <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">
                            Only one term can be active at a time.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">Save Changes</button>
                    <a href="{{ route('admin.terms.show', $term) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
