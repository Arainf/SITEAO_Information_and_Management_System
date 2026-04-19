<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold" style="color: #181d26; letter-spacing: 0.12px;">Profile</h1>
        <p class="text-sm mt-0.5" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">Manage your account information</p>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto space-y-6">

        <div class="sims-card p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="sims-card p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="sims-card p-6">
            @include('profile.partials.delete-user-form')
        </div>

        <div class="sims-card overflow-hidden">
            @include('profile.partials.my-certificates')
        </div>

    </div>
</x-app-layout>
