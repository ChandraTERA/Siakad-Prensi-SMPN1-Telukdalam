<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h2>
        <p class="text-gray-600">Enter your new password below</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email" class="pl-10" type="email" name="email" :value="old('email', $request->email)" required
                    autofocus autocomplete="username" placeholder="Enter your email address" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <x-text-input id="password" class="pl-10" type="password" name="password" required
                    autocomplete="new-password" placeholder="Enter your new password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <x-text-input id="password_confirmation" class="pl-10" type="password" name="password_confirmation"
                    required autocomplete="new-password" placeholder="Confirm your new password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button class="w-full">
                <i class="fas fa-key mr-2"></i>
                {{ __('Reset Password') }}
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
