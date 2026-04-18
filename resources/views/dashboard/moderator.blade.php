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
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sims-card p-6 flex flex-col gap-3 opacity-50 cursor-not-allowed">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(4,14,32,0.05);">📅</div>
                <div>
                    <p class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">Create Event</p>
                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Tag users and define eligibility</p>
                </div>
                <span class="text-xs font-medium mt-auto" style="color: rgba(4,14,32,0.35); letter-spacing: 0.07px;">Coming soon</span>
            </div>

            <div class="sims-card p-6 flex flex-col gap-3 opacity-50 cursor-not-allowed">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(4,14,32,0.05);">👤</div>
                <div>
                    <p class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">Manage Participants</p>
                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Assign users to committees</p>
                </div>
                <span class="text-xs font-medium mt-auto" style="color: rgba(4,14,32,0.35); letter-spacing: 0.07px;">Coming soon</span>
            </div>
        </div>
    </div>
</x-app-layout>
