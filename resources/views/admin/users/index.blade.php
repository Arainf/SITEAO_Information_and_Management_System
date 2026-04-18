<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">User Management</h1>
                <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Manage roles, approvals and account access
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-5">

        {{-- Flash messages --}}
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

        {{-- Pending approval queue --}}
        @php
            $pendingUsers = $users->getCollection()->where('role', 'pending');
        @endphp
        @if($pendingUsers->count())
            <div class="rounded-sims-card p-6" style="background: #fffbeb; border: 1px solid #fde68a;">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: #92400e;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-sm font-semibold" style="color: #92400e; letter-spacing: 0.08px;">
                        Pending Approval &mdash; {{ $pendingUsers->count() }} {{ $pendingUsers->count() === 1 ? 'account' : 'accounts' }}
                    </h3>
                </div>
                <div class="space-y-3">
                    @foreach($pendingUsers as $user)
                        <div class="flex items-center justify-between py-3 px-4 rounded-xl" style="background: rgba(255,255,255,0.7); border: 1px solid #fde68a;">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                     style="background-color: #d97706;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium truncate" style="color: #181d26; letter-spacing: 0.08px;">{{ $user->name }}</p>
                                    <p class="text-xs truncate" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">{{ $user->email }}</p>
                                    <p class="text-xs mt-0.5" style="color: rgba(4,14,32,0.5); letter-spacing: 0.07px;">Registered {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn-success">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger"
                                            onclick="return confirm('Permanently delete this pending account?')">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Filter bar --}}
        <div class="sims-card px-5 py-4">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-medium" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Role</label>
                    <select name="role"
                        class="rounded-xl border text-sm py-1.5 px-3 transition-all duration-150"
                        style="border-color: #e0e2e6; color: #181d26; background: #fff; letter-spacing: 0.08px;">
                        <option value="">All</option>
                        @foreach($roles as $r)
                            @if($r !== 'pending')
                                <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-medium" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Status</label>
                    <select name="status"
                        class="rounded-xl border text-sm py-1.5 px-3 transition-all duration-150"
                        style="border-color: #e0e2e6; color: #181d26; background: #fff; letter-spacing: 0.08px;">
                        <option value="">All</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="padding: 6px 16px; border-radius: 10px; font-size: 13px;">
                    Filter
                </button>
                @if(request()->hasAny(['role', 'status']))
                    <a href="{{ route('admin.users.index') }}"
                       class="text-xs font-medium hover:underline no-underline"
                       style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">
                        Clear filters
                    </a>
                @endif
            </form>
        </div>

        {{-- Users table --}}
        <div class="sims-card overflow-hidden">
            <table class="min-w-full sims-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users->getCollection()->where('role', '!=', 'pending') as $user)
                        <tr class="{{ $user->status === 'inactive' ? 'opacity-60' : '' }}">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                         style="background-color: #1b61c9;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <p class="text-sm font-medium truncate" style="color: #181d26; letter-spacing: 0.08px;">
                                                {{ $user->name }}
                                            </p>
                                            @if($user->id === auth()->id())
                                                <span class="text-xs" style="color: #1b61c9; letter-spacing: 0.07px;">(you)</span>
                                            @endif
                                        </div>
                                        <p class="text-xs truncate" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()"
                                            class="rounded-xl border text-xs py-1.5 px-2.5 transition-all duration-150"
                                            style="border-color: #e0e2e6; color: #181d26; background: #fff; letter-spacing: 0.07px;">
                                            @foreach($roles as $r)
                                                @if($r !== 'pending')
                                                    <option value="{{ $r }}" @selected($user->role === $r)>{{ ucfirst($r) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $user->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>

                            <td>
                                <span class="text-xs" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">
                                    {{ $user->created_at->format('M d, Y') }}
                                </span>
                            </td>

                            <td>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-xs font-medium hover:underline transition-all duration-150"
                                            style="color: {{ $user->status === 'active' ? '#991b1b' : '#166534' }}; letter-spacing: 0.07px;">
                                            {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                @else
                                    <span style="color: rgba(4,14,32,0.25);">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <p class="text-sm" style="color: rgba(4,14,32,0.5); letter-spacing: 0.08px;">No users found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="px-6 py-4" style="border-top: 1px solid #e0e2e6;">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
