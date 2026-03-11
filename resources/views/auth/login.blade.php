<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back</h2>
        <p class="text-gray-600">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <!-- Loading Overlay -->
    <div id="login-loading" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700">Signing in...</span>
        </div>
    </div>

    <!-- Error Alert -->
    <div id="login-error" class="hidden mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <div class="flex">
            <svg class="w-5 h-5 mr-2 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-medium">Login Failed</p>
                <p id="login-error-message" class="text-sm"></p>
            </div>
        </div>
    </div>

    <form id="login-form" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email" class="pl-10" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" placeholder="Enter your email" />
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
                    autocomplete="current-password" placeholder="Enter your password" />
            </div>
            <div id="password-error" class="hidden mt-2 text-sm text-red-600"></div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0"
                    name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div>
            <button type="submit" id="login-button"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-sign-in-alt mr-2"></i>
                <span id="login-button-text">{{ __('Sign In') }}</span>
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                        class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                        {{ __('Create Account') }}
                    </a>
                </p>
            </div>
        @endif
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const loginButton = document.getElementById('login-button');
            const loginButtonText = document.getElementById('login-button-text');
            const loginLoading = document.getElementById('login-loading');
            const loginError = document.getElementById('login-error');
            const loginErrorMessage = document.getElementById('login-error-message');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');

            // Hide error messages initially
            function hideErrors() {
                loginError.classList.add('hidden');
                emailError.classList.add('hidden');
                passwordError.classList.add('hidden');
            }

            // Show error message
            function showError(message, field = null) {
                hideErrors();
                loginErrorMessage.textContent = message;
                loginError.classList.remove('hidden');

                if (field === 'email') {
                    emailError.textContent = message;
                    emailError.classList.remove('hidden');
                } else if (field === 'password') {
                    passwordError.textContent = message;
                    passwordError.classList.remove('hidden');
                }
            }

            // Show loading state
            function showLoading() {
                loginButton.disabled = true;
                loginButtonText.textContent = 'Signing in...';
                loginLoading.classList.remove('hidden');
            }

            // Hide loading state
            function hideLoading() {
                loginButton.disabled = false;
                loginButtonText.textContent = 'Sign In';
                loginLoading.classList.add('hidden');
            }

            // Handle form submission
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                hideErrors();
                showLoading();

                const formData = new FormData(loginForm);
                const loginData = {
                    email: formData.get('email'),
                    password: formData.get('password'),
                    remember: formData.get('remember') === 'on'
                };

                try {
                    const response = await fetch('/api/auth/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(loginData)
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.success) {
                        // Login successful - redirect
                        window.location.href = data.redirect_url;
                    } else {
                        // Login failed - show error
                        if (data.errors) {
                            if (data.errors.email) {
                                showError(data.errors.email[0], 'email');
                            } else if (data.errors.password) {
                                showError(data.errors.password[0], 'password');
                            } else {
                                showError(data.message || 'Login failed');
                            }
                        } else {
                            showError(data.message || 'Login failed');
                        }
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    if (error.message.includes('HTTP error')) {
                        showError(`Server error (${error.message}). Please try again.`);
                    } else if (error.message.includes('Failed to fetch')) {
                        showError('Network error. Please check your connection and try again.');
                    } else {
                        showError(`Error: ${error.message}`);
                    }
                } finally {
                    hideLoading();
                }
            });

            // Clear errors when user starts typing
            document.getElementById('email').addEventListener('input', hideErrors);
            document.getElementById('password').addEventListener('input', hideErrors);
        });
    </script>
</x-guest-layout>
