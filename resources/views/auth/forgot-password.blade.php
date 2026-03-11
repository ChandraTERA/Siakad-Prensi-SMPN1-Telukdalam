<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Forgot Password?</h2>
        <p class="text-gray-600">No problem! Enter your email address and we'll send you a password reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email" class="pl-10" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" placeholder="Enter your email address" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button class="w-full">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Remember your password?
                <a href="{{ route('login') }}"
                    class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                    {{ __('Back to Login') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
