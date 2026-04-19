<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Assignments</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Pending moderator assignment requests
                </p>
            </div>
            <a href="{{ route('admin.assignments.create') }}" class="btn-primary">Assign Position</a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-5">

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

        @if($pending->count())
            <div class="rounded-sims-card p-6" style="background: #fffbeb; border: 1px solid #fde68a;">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #92400e;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-sm font-semibold" style="color: #92400e; letter-spacing: 0.08px;">
                        Pending Approval — {{ $pending->count() }} {{ Str::plural('request', $pending->count()) }}
                    </h3>
                </div>
                <div class="space-y-3">
                    @foreach($pending as $assignment)
                        <div class="flex flex-wrap items-center justify-between gap-3 py-3 px-4 rounded-xl"
                             style="background: rgba(255,255,255,0.7); border: 1px solid #fde68a;">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                     style="background-color: #1b61c9;">
                                    {{ strtoupper(substr($assignment->user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold truncate" style="color: #181d26; letter-spacing: 0.08px;">
                                        {{ $assignment->user->name }}
                                    </p>
                                    <p class="text-xs truncate" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">
                                        {{ $assignment->user->email }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0 px-2">
                                <p class="text-xs font-medium" style="color: rgba(4,14,32,0.55); letter-spacing: 0.07px;">Position</p>
                                <p class="text-sm font-semibold" style="color: #181d26; letter-spacing: 0.08px;">
                                    {{ $assignment->position->name }}
                                </p>
                                @if($assignment->position->committee)
                                    <p class="text-xs" style="color: #1b61c9; letter-spacing: 0.07px;">
                                        {{ $assignment->position->committee->name }}
                                    </p>
                                @endif
                            </div>

                            <div class="text-right flex-shrink-0">
                                <p class="text-xs" style="color: rgba(4,14,32,0.5); letter-spacing: 0.07px;">
                                    Requested by <span class="font-medium">{{ $assignment->assignedBy->name }}</span>
                                </p>
                                <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.4); letter-spacing: 0.07px;">
                                    {{ $assignment->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <form method="POST" action="{{ route('admin.assignments.approve', $assignment) }}">
                                    @csrf
                                    <button type="submit" class="btn-success">Approve</button>
                                </form>

                                <form method="POST" action="{{ route('admin.assignments.reject', $assignment) }}"
                                      x-data="{ open: false }" @submit.prevent="open = false; $el.submit()">
                                    @csrf
                                    <div x-show="!open">
                                        <button type="button" @click="open = true" class="btn-danger">Reject</button>
                                    </div>
                                    <div x-show="open" class="flex items-center gap-2">
                                        <input type="text" name="remarks" placeholder="Reason (optional)"
                                               class="sims-input text-sm" style="padding: 5px 10px; width: 160px;" />
                                        <button type="submit" class="btn-danger" style="font-size: 12px; padding: 5px 10px;">Confirm</button>
                                        <button type="button" @click="open = false" class="btn-secondary" style="font-size: 12px; padding: 5px 10px;">✕</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="sims-card py-20 text-center">
                <p class="text-sm" style="color: rgba(4,14,32,0.5); letter-spacing: 0.08px;">No pending assignment requests.</p>
            </div>
        @endif

    </div>
</x-app-layout>
