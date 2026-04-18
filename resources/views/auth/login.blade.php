<x-guest-layout>
    <div class="w-full" style="max-width: 400px;">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-semibold mb-1.5" style="color: #181d26; letter-spacing: 0.12px;">Sign in to SIMS</h1>
            <p class="text-sm" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                Don't have an account?
                <a href="{{ route('register') }}" style="color: #1b61c9;" class="font-medium hover:underline">Create one</a>
            </p>
        </div>

        <!-- Card -->
        <div class="sims-card px-8 py-8">
            <x-auth-session-status class="mb-5" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color: #181d26; letter-spacing: 0.08px;">
                        Email address
                    </label>
                    <input id="email" type="email" name="email" :value="old('email')"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="sims-input"
                           placeholder="you@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium" style="color: #181d26; letter-spacing: 0.08px;">
                            Password
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs font-medium no-underline hover:underline"
                               style="color: #1b61c9; letter-spacing: 0.07px;">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           class="sims-input"
                           placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                <!-- Remember me -->
                <div class="flex items-center gap-2">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="rounded text-sm"
                           style="border-color: #e0e2e6; accent-color: #1b61c9;" />
                    <label for="remember_me" class="text-sm" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                        Remember me
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-primary w-full mt-2">
                    Sign in
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
