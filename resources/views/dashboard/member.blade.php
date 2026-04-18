<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Dashboard</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Welcome back, {{ auth()->user()->name }}
                </p>
            </div>
            <span class="badge badge-member">Member</span>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('events.index') }}"
               class="sims-card p-6 flex flex-col gap-3 no-underline"
               onmouseover="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.12) 0px 2px 6px, rgba(45,127,249,0.36) 0px 2px 8px'"
               onmouseout="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.08) 0px 0px 2px, rgba(45,127,249,0.28) 0px 1px 3px, rgba(0,0,0,0.06) 0px 0px 0px 0.5px inset'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(27,97,201,0.08);">📋</div>
                <div>
                    <p class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">My Events</p>
                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Events you're eligible to join</p>
                </div>
                <div class="flex items-center gap-1 text-xs font-medium mt-auto" style="color: #1b61c9; letter-spacing: 0.07px;">
                    Browse events
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <div class="sims-card p-6 flex flex-col gap-3 opacity-50 cursor-not-allowed">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(4,14,32,0.05);">🏆</div>
                <div>
                    <p class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">My Achievements</p>
                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Your verified participation record</p>
                </div>
                <span class="text-xs font-medium mt-auto" style="color: rgba(4,14,32,0.35); letter-spacing: 0.07px;">Coming soon</span>
            </div>
        </div>

        <!-- Profile card -->
        <div class="sims-card p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white text-lg font-semibold flex-shrink-0"
                 style="background-color: #1b61c9;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">{{ auth()->user()->name }}</p>
                <p class="text-xs mt-0.5 truncate" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn-outline text-xs px-4 py-2 no-underline" style="border-radius: 10px;">
                Edit profile
            </a>
        </div>
    </div>
</x-app-layout>
