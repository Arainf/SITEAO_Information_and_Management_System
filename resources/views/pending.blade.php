<x-guest-layout>
    <div class="w-full text-center" style="max-width: 380px;">
        <div class="sims-card px-8 py-10">
            <!-- Icon -->
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5"
                 style="background: #fef9c3; border: 1px solid #fde68a;">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color: #92400e;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
            </div>

            <h2 class="text-lg font-semibold mb-2" style="color: #181d26; letter-spacing: 0.12px;">
                Account Pending Approval
            </h2>
            <p class="text-sm mb-6 leading-relaxed" style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                Your account has been created and is awaiting administrator approval.
                You'll have full access once your account is activated.
            </p>

            <div class="rounded-xl px-4 py-3 mb-6 text-left" style="background: #f8fafc; border: 1px solid #e0e2e6;">
                <p class="text-xs font-medium" style="color: rgba(4,14,32,0.69); letter-spacing: 0.07px;">Signed in as</p>
                <p class="text-sm font-medium mt-0.5 truncate" style="color: #181d26; letter-spacing: 0.08px;">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm font-medium hover:underline transition-all duration-150"
                    style="color: rgba(4,14,32,0.69); letter-spacing: 0.08px;">
                    Sign out
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
