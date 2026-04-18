<x-guest-layout>
    <div class="w-full" style="max-width: 420px;">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-semibold mb-1.5" style="color: #181d26; letter-spacing: 0.12px;">Create your account</h1>
            <p class="text-sm" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                Already have an account?
                <a href="{{ route('login') }}" style="color: #1b61c9;" class="font-medium hover:underline">Sign in</a>
            </p>
        </div>

        <!-- Notice -->
        <div class="mb-5 px-4 py-3 rounded-xl text-sm" style="background: rgba(27,97,201,0.06); border: 1px solid rgba(27,97,201,0.15); color: #254fad; letter-spacing: 0.08px;">
            Your account will require admin approval before you can access the system.
        </div>

        <!-- Card -->
        <div class="sims-card px-8 py-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-1.5" style="color: #181d26; letter-spacing: 0.08px;">
                        Full name
                    </label>
                    <input id="name" type="text" name="name"
                           value="{{ old('name') }}"
                           required autofocus autocomplete="name"
                           class="sims-input"
                           placeholder="Juan dela Cruz" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color: #181d26; letter-spacing: 0.08px;">
                        Email address
                    </label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           required autocomplete="username"
                           class="sims-input"
                           placeholder="you@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-1.5" style="color: #181d26; letter-spacing: 0.08px;">
                        Password
                    </label>
                    <input id="password" type="password" name="password"
                           required autocomplete="new-password"
                           class="sims-input"
                           placeholder="Min. 8 characters" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1.5" style="color: #181d26; letter-spacing: 0.08px;">
                        Confirm password
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           required autocomplete="new-password"
                           class="sims-input"
                           placeholder="Re-enter your password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-primary w-full mt-2">
                    Create account
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
