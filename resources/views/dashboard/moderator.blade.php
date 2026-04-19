<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Dashboard</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Welcome back, {{ auth()->user()->name }}
                </p>
            </div>
            <span class="badge badge-moderator">Moderator</span>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

        <!-- Quick actions -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('events.create') }}"
               class="sims-card p-6 flex flex-col gap-3 no-underline"
               onmouseover="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.12) 0px 2px 6px, rgba(45,127,249,0.36) 0px 2px 8px'"
               onmouseout="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.08) 0px 0px 2px, rgba(45,127,249,0.28) 0px 1px 3px, rgba(0,0,0,0.06) 0px 0px 0px 0.5px inset'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(27,97,201,0.08);">📅</div>
                <div>
                    <p class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">Create Event</p>
                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Tag users and define eligibility</p>
                </div>
                <div class="flex items-center gap-1 text-xs font-medium mt-auto" style="color: #1b61c9; letter-spacing: 0.07px;">
                    Create
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('events.index') }}"
               class="sims-card p-6 flex flex-col gap-3 no-underline"
               onmouseover="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.12) 0px 2px 6px, rgba(45,127,249,0.36) 0px 2px 8px'"
               onmouseout="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.08) 0px 0px 2px, rgba(45,127,249,0.28) 0px 1px 3px, rgba(0,0,0,0.06) 0px 0px 0px 0.5px inset'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(27,97,201,0.08);">📋</div>
                <div>
                    <p class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">Events</p>
                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Browse and manage events</p>
                </div>
                <div class="flex items-center gap-1 text-xs font-medium mt-auto" style="color: #1b61c9; letter-spacing: 0.07px;">
                    Open
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('validation.index') }}"
               class="sims-card p-6 flex flex-col gap-3 no-underline"
               onmouseover="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.12) 0px 2px 6px, rgba(45,127,249,0.36) 0px 2px 8px'"
               onmouseout="this.style.boxShadow='rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.08) 0px 0px 2px, rgba(45,127,249,0.28) 0px 1px 3px, rgba(0,0,0,0.06) 0px 0px 0px 0.5px inset'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(27,97,201,0.08);">✅</div>
                <div>
                    <p class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">Validation Queue</p>
                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Review proof submissions</p>
                </div>
                <div class="flex items-center gap-1 text-xs font-medium mt-auto" style="color: #1b61c9; letter-spacing: 0.07px;">
                    @if($pendingCount > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-xs font-semibold mr-1"
                              style="background: rgba(27,97,201,0.1); color: #1b61c9;">{{ $pendingCount }}</span>
                    @endif
                    Open queue
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Stats -->
        <div class="sims-card p-6">
            <h2 class="text-sm font-semibold mb-4" style="color: #181d26; letter-spacing: 0.08px;">Your Activity</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center py-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e0e2e6;">
                    <p class="text-2xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">{{ $myEvents }}</p>
                    <p class="text-xs mt-1" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Events Created</p>
                </div>
                <div class="text-center py-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e0e2e6;">
                    <p class="text-2xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">{{ $myParticipants }}</p>
                    <p class="text-xs mt-1" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Total Participants</p>
                </div>
                <div class="text-center py-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e0e2e6;">
                    <p class="text-2xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">{{ $pendingCount }}</p>
                    <p class="text-xs mt-1" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Pending Validation</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
