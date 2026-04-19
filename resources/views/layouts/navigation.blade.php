<nav x-data="{ open: false }" style="background: #fff; border-bottom: 1px solid #e0e2e6;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14">

            <!-- Left: Logo + Nav links -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 no-underline">
                    <div class="flex items-center justify-center w-7 h-7 rounded-lg text-white text-xs font-bold flex-shrink-0"
                         style="background-color: #1b61c9;">S</div>
                    <span class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">SIMS</span>
                </a>

                <!-- Desktop nav links -->
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                       style="letter-spacing: 0.08px; color: {{ request()->routeIs('dashboard') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('dashboard') ? 'rgba(27,97,201,0.07)' : 'transparent' }};">
                        Dashboard
                    </a>

                    <a href="{{ route('events.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                       style="letter-spacing: 0.08px; color: {{ request()->routeIs('events.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('events.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }};">
                        Events
                    </a>

                    @if(auth()->user()->hasRole(['admin', 'moderator', 'officer']))
                        <a href="{{ route('validation.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                           style="letter-spacing: 0.08px; color: {{ request()->routeIs('validation.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('validation.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }};">
                            Validation
                        </a>
                    @endif

                    <a href="{{ route('administration.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                       style="letter-spacing: 0.08px; color: {{ request()->routeIs('administration.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('administration.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }};">
                        Administration
                    </a>

                    @if(auth()->user()->hasRole(['admin', 'moderator']))
                        <a href="{{ route('admin.assignments.create') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                           style="letter-spacing: 0.08px; color: {{ request()->routeIs('admin.assignments.create') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('admin.assignments.create') ? 'rgba(27,97,201,0.07)' : 'transparent' }};">
                            Assign
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                           style="letter-spacing: 0.08px; color: {{ request()->routeIs('admin.users.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('admin.users.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }};">
                            Users
                        </a>
                        <a href="{{ route('admin.terms.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                           style="letter-spacing: 0.08px; color: {{ request()->routeIs('admin.terms.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('admin.terms.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }};">
                            Terms
                        </a>
                        <a href="{{ route('admin.committees.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                           style="letter-spacing: 0.08px; color: {{ request()->routeIs('admin.committees.*') || request()->routeIs('admin.positions.*') || request()->routeIs('admin.assignments.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('admin.committees.*') || request()->routeIs('admin.positions.*') || request()->routeIs('admin.assignments.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }};">
                            Org
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right: User menu -->
            <div class="hidden sm:flex items-center gap-3">
                <!-- Role badge -->
                <span class="badge badge-{{ auth()->user()->role }}" style="letter-spacing: 0.07px;">
                    {{ ucfirst(auth()->user()->role) }}
                </span>

                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-sm font-medium transition-all duration-150"
                                style="color: #181d26; border: 1px solid #e0e2e6; letter-spacing: 0.08px; background: #fff;"
                                onmouseover="this.style.boxShadow='rgba(15,48,106,0.05) 0px 0px 20px'"
                                onmouseout="this.style.boxShadow='none'">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}"
                                     alt="Avatar"
                                     class="w-6 h-6 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                     style="background-color: #1b61c9;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="max-w-28 truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3" style="border-bottom: 1px solid #e0e2e6;">
                            <p class="text-xs font-medium" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Signed in as</p>
                            <p class="text-sm font-semibold truncate mt-0.5" style="color: #181d26; letter-spacing: 0.08px;">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" style="letter-spacing: 0.08px;">
                                Profile
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        style="letter-spacing: 0.08px;">
                                    Sign out
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-lg transition duration-150"
                        style="color: rgba(4,14,32,0.69);">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden" style="border-top: 1px solid #e0e2e6;">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
               style="color: {{ request()->routeIs('dashboard') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('dashboard') ? 'rgba(27,97,201,0.07)' : 'transparent' }}; letter-spacing: 0.08px;">
                Dashboard
            </a>

            <a href="{{ route('events.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
               style="color: {{ request()->routeIs('events.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('events.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }}; letter-spacing: 0.08px;">
                Events
            </a>

            @if(auth()->user()->hasRole(['admin', 'moderator', 'officer']))
                <a href="{{ route('validation.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                   style="color: {{ request()->routeIs('validation.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('validation.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }}; letter-spacing: 0.08px;">
                    Validation
                </a>
            @endif

            <a href="{{ route('administration.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
               style="color: {{ request()->routeIs('administration.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('administration.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }}; letter-spacing: 0.08px;">
                Administration
            </a>

            @if(auth()->user()->hasRole(['admin', 'moderator']))
                <a href="{{ route('admin.assignments.create') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                   style="color: {{ request()->routeIs('admin.assignments.create') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('admin.assignments.create') ? 'rgba(27,97,201,0.07)' : 'transparent' }}; letter-spacing: 0.08px;">
                    Assign Position
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                   style="color: {{ request()->routeIs('admin.users.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('admin.users.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }}; letter-spacing: 0.08px;">
                    Users
                </a>
                <a href="{{ route('admin.terms.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                   style="color: {{ request()->routeIs('admin.terms.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('admin.terms.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }}; letter-spacing: 0.08px;">
                    Terms
                </a>
                <a href="{{ route('admin.committees.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 no-underline"
                   style="color: {{ request()->routeIs('admin.committees.*') || request()->routeIs('admin.positions.*') || request()->routeIs('admin.assignments.*') ? '#1b61c9' : 'rgba(4,14,32,0.69)' }}; background: {{ request()->routeIs('admin.committees.*') || request()->routeIs('admin.positions.*') || request()->routeIs('admin.assignments.*') ? 'rgba(27,97,201,0.07)' : 'transparent' }}; letter-spacing: 0.08px;">
                    Org
                </a>
            @endif
        </div>

        <div class="px-4 py-3" style="border-top: 1px solid #e0e2e6;">
            <div class="flex items-center gap-3 mb-3">
                @if(Auth::user()->avatar)
                    <img src="{{ Storage::url(Auth::user()->avatar) }}"
                         alt="Avatar"
                         class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                         style="background-color: #1b61c9;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-sm font-medium" style="color: #181d26; letter-spacing: 0.08px;">{{ Auth::user()->name }}</p>
                    <p class="text-xs" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" style="letter-spacing: 0.08px;">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            style="letter-spacing: 0.08px;">
                        Sign out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
