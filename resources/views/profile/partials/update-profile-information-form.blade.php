<section>
    <header class="mb-6">
        <h2 class="text-sm font-semibold" style="color: #181d26; letter-spacing: 0.08px;">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-xs" style="color: rgba(4,14,32,0.69);">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('patch')

        <!-- Avatar -->
        <div>
            <x-input-label :value="__('Profile Photo')" />
            <div class="mt-2 flex items-center gap-4">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}"
                         alt="Avatar"
                         class="w-16 h-16 rounded-full object-cover"
                         style="border: 2px solid #e0e2e6;">
                @else
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-xl font-semibold"
                         style="background: rgba(27,97,201,0.1); color: #1b61c9; border: 2px solid #e0e2e6;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1">
                    <input id="avatar" name="avatar" type="file" accept="image/*"
                        class="block w-full text-sm rounded-xl border px-3 py-2"
                        style="color: #181d26; border-color: #e0e2e6; background: #fff;">
                    <p class="text-xs mt-1" style="color: rgba(4,14,32,0.55);">JPEG or PNG, max 2 MB</p>
                </div>
            </div>
            <x-input-error class="mt-1" :messages="$errors->get('avatar')" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-xs" style="color: rgba(4,14,32,0.69);">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification"
                            class="underline text-xs font-medium"
                            style="color: #1b61c9;">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-xs font-medium" style="color: #2a9d5c;">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" rows="3"
                class="sims-input mt-1 block w-full resize-none"
                maxlength="1000"
                placeholder="Tell us a bit about yourself…">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-1" :messages="$errors->get('bio')" />
        </div>

        <!-- Committee -->
        <div>
            <x-input-label for="committee" :value="__('Committee')" />
            <x-text-input id="committee" name="committee" type="text" class="mt-1 block w-full"
                :value="old('committee', $user->committee)"
                placeholder="e.g. Academic Affairs" />
            <x-input-error class="mt-1" :messages="$errors->get('committee')" />
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit" class="btn-primary">{{ __('Save Changes') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm font-medium" style="color: #2a9d5c;">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
