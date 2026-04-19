<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('events.index') }}"
               class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
               style="color: rgba(4,14,32,0.69); background: rgba(4,14,32,0.04);"
               onmouseover="this.style.background='rgba(4,14,32,0.08)'"
               onmouseout="this.style.background='rgba(4,14,32,0.04)'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Create Event</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">Fill in the details below</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" x-data="tagSearch()" @submit="prepareTaggedUsers">
            @csrf

            <div class="sims-card p-6 space-y-5">

                <!-- Title -->
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                        :value="old('title')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                </div>

                <!-- Description -->
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4"
                        class="sims-input mt-1 block w-full resize-none"
                        required>{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>

                <!-- Event Date + Location -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="event_date" :value="__('Event Date')" />
                        <x-text-input id="event_date" name="event_date" type="datetime-local" class="mt-1 block w-full"
                            :value="old('event_date')" required />
                        <x-input-error :messages="$errors->get('event_date')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="location" :value="__('Location')" />
                        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
                            :value="old('location')" />
                        <x-input-error :messages="$errors->get('location')" class="mt-1" />
                    </div>
                </div>

                <!-- Term -->
                <div>
                    <x-input-label for="term_id" :value="__('Term')" />
                    <select id="term_id" name="term_id" class="sims-input mt-1 block w-full">
                        <option value="">— No term —</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" @selected(old('term_id', $activeTerm?->id) == $term->id)>
                                {{ $term->name }}{{ $term->is_active ? ' (Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('term_id')" class="mt-1" />
                </div>

                <!-- Status -->
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="sims-input mt-1 block w-full">
                        @foreach(['draft' => 'Draft', 'open' => 'Open', 'closed' => 'Closed'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('status', 'draft') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>

                <!-- Certificate Template -->
                <div>
                    <x-input-label for="cert_template" :value="__('Certificate Template (PNG, optional)')" />
                    <p class="text-xs mt-0.5 mb-2" style="color: rgba(4,14,32,0.55);">
                        Upload a PNG. Participant name, event date, and org name will be overlaid automatically when generating certificates.
                    </p>
                    <input id="cert_template" name="cert_template" type="file" accept="image/png"
                           class="mt-1 block w-full text-sm rounded-xl border px-3 py-2"
                           style="color: #181d26; border-color: #e0e2e6; background: #fff;">
                    <x-input-error :messages="$errors->get('cert_template')" class="mt-1" />
                </div>

                <!-- Facebook Post URL -->
                <div>
                    <x-input-label for="fb_post_url" :value="__('Facebook Post URL (optional)')" />
                    <p class="text-xs mt-0.5 mb-1" style="color: rgba(4,14,32,0.55);">
                        Paste the URL of the official Facebook post for this event. It will be embedded on the event page.
                    </p>
                    <x-text-input id="fb_post_url" name="fb_post_url" type="url" class="mt-1 block w-full"
                        :value="old('fb_post_url')"
                        placeholder="https://www.facebook.com/…/posts/…" />
                    <x-input-error :messages="$errors->get('fb_post_url')" class="mt-1" />
                </div>

                <!-- Tag Users -->
                <div>
                    <x-input-label :value="__('Tag Participants (optional)')" />
                    <p class="text-xs mt-0.5 mb-2" style="color: rgba(4,14,32,0.55);">Search and add members to pre-register them for this event.</p>

                    <!-- Search input -->
                    <div class="relative">
                        <x-text-input type="text" class="block w-full" placeholder="Search by name or email…"
                            x-model="query" @input.debounce.300ms="search" @keydown.escape="results = []" />
                        <!-- Dropdown results -->
                        <div x-show="results.length > 0"
                             class="absolute z-10 mt-1 w-full rounded-xl border py-1 shadow-lg"
                             style="background: #fff; border-color: #e0e2e6; box-shadow: rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.12) 0px 4px 12px;">
                            <template x-for="user in results" :key="user.id">
                                <button type="button"
                                    @click="addUser(user)"
                                    class="w-full text-left px-4 py-2.5 text-sm flex items-center gap-3 transition-colors"
                                    style="color: #181d26;"
                                    onmouseover="this.style.background='rgba(27,97,201,0.06)'"
                                    onmouseout="this.style.background='transparent'">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold shrink-0"
                                         style="background: rgba(27,97,201,0.1); color: #1b61c9;">
                                        <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium truncate" x-text="user.name"></p>
                                        <p class="text-xs truncate" style="color: rgba(4,14,32,0.55);" x-text="user.email"></p>
                                    </div>
                                    <span class="text-xs ml-auto shrink-0" style="color: rgba(4,14,32,0.35);" x-text="user.committee ?? ''"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Tagged users chips -->
                    <div class="flex flex-wrap gap-2 mt-3" x-show="tagged.length > 0">
                        <template x-for="user in tagged" :key="user.id">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium"
                                  style="background: rgba(27,97,201,0.08); color: #1b61c9; border: 1px solid rgba(27,97,201,0.2);">
                                <span x-text="user.name"></span>
                                <button type="button" @click="removeUser(user.id)"
                                    class="w-3.5 h-3.5 rounded-full flex items-center justify-center transition-colors"
                                    style="color: #1b61c9;"
                                    onmouseover="this.style.color='#d72b2b'"
                                    onmouseout="this.style.color='#1b61c9'">
                                    ×
                                </button>
                                <input type="hidden" name="tagged_users[]" :value="user.id">
                            </span>
                        </template>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-4">
                <a href="{{ route('events.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create Event</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function tagSearch() {
        return {
            query: '',
            results: [],
            tagged: [],
            async search() {
                if (this.query.length < 2) { this.results = []; return; }
                const res = await fetch(`/api/users/search?q=${encodeURIComponent(this.query)}`);
                const data = await res.json();
                this.results = data.filter(u => !this.tagged.find(t => t.id === u.id));
            },
            addUser(user) {
                if (!this.tagged.find(t => t.id === user.id)) {
                    this.tagged.push(user);
                }
                this.results = [];
                this.query = '';
            },
            removeUser(id) {
                this.tagged = this.tagged.filter(u => u.id !== id);
            },
            prepareTaggedUsers() {
                // hidden inputs are rendered via x-for, nothing extra needed
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
