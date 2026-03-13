<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Create Account</h2>
        <p class="text-gray-600">Join SIAKAD today</p>
    </div>

    <!-- Loading Overlay -->
    <div id="register-loading" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700">Creating account...</span>
        </div>
    </div>

    <!-- Error Alert -->
    <div id="register-error" class="hidden mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <div class="flex">
            <svg class="w-5 h-5 mr-2 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-medium">Registration Failed</p>
                <p id="register-error-message" class="text-sm"></p>
            </div>
        </div>
    </div>

    <form id="register-form" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <x-text-input id="name" class="pl-10" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" placeholder="Enter your full name" />
            </div>
            <div id="name-error" class="hidden mt-2 text-sm text-red-600"></div>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email" class="pl-10" type="email" name="email" :value="old('email')" required
                    autocomplete="username" placeholder="Enter your email" />
            </div>
            <div id="email-error" class="hidden mt-2 text-sm text-red-600"></div>
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <x-text-input id="password" class="pl-10" type="password" name="password" required
                    autocomplete="new-password" placeholder="Create a password" />
            </div>
            <div id="password-error" class="hidden mt-2 text-sm text-red-600"></div>
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <x-text-input id="password_confirmation" class="pl-10" type="password" name="password_confirmation"
                    required autocomplete="new-password" placeholder="Confirm your password" />
            </div>
            <div id="password_confirmation-error" class="hidden mt-2 text-sm text-red-600"></div>
        </div>

        <!-- Register Button -->
        <div>
            <button type="submit" id="register-button"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-user-plus mr-2"></i>
                <span id="register-button-text">{{ __('Create Account') }}</span>
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('register-form');
            const registerButton = document.getElementById('register-button');
            const registerButtonText = document.getElementById('register-button-text');
            const registerLoading = document.getElementById('register-loading');
            const registerError = document.getElementById('register-error');
            const registerErrorMessage = document.getElementById('register-error-message');
            const nameError = document.getElementById('name-error');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const passwordConfirmationError = document.getElementById('password_confirmation-error');

            // Hide error messages initially
            function hideErrors() {
                registerError.classList.add('hidden');
                nameError.classList.add('hidden');
                emailError.classList.add('hidden');
                passwordError.classList.add('hidden');
                passwordConfirmationError.classList.add('hidden');
            }

            // Show error message
            function showError(message, field = null) {
                hideErrors();
                registerErrorMessage.textContent = message;
                registerError.classList.remove('hidden');

                if (field) {
                    const errorElement = document.getElementById(field + '-error');
                    if (errorElement) {
                        errorElement.textContent = message;
                        errorElement.classList.remove('hidden');
                    }
                }
            }

            // Show loading state
            function showLoading() {
                registerButton.disabled = true;
                registerButtonText.textContent = 'Creating account...';
                registerLoading.classList.remove('hidden');
            }

            // Hide loading state
            function hideLoading() {
                registerButton.disabled = false;
                registerButtonText.textContent = 'Create Account';
                registerLoading.classList.add('hidden');
            }

            // Handle form submission
            registerForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                hideErrors();
                showLoading();

                const formData = new FormData(registerForm);
                const registerData = {
                    name: formData.get('name'),
                    email: formData.get('email'),
                    password: formData.get('password'),
                    password_confirmation: formData.get('password_confirmation')
                };

                try {
                    const response = await fetch('/api/auth/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(registerData)
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Registration successful - redirect
                        window.location.href = data.redirect_url;
                    } else {
                        // Registration failed - show error
                        if (data.errors) {
                            // Show first error
                            const firstError = Object.keys(data.errors)[0];
                            showError(data.errors[firstError][0], firstError);
                        } else {
                            showError(data.message || 'Registration failed');
                        }
                    }
                } catch (error) {
                    console.error('Registration error:', error);
                    showError('Network error. Please try again.');
                } finally {
                    hideLoading();
                }
            });

            // Clear errors when user starts typing
            document.getElementById('name').addEventListener('input', hideErrors);
            document.getElementById('email').addEventListener('input', hideErrors);
            document.getElementById('password').addEventListener('input', hideErrors);
            document.getElementById('password_confirmation').addEventListener('input', hideErrors);
        });
    </script>
</x-guest-layout>
