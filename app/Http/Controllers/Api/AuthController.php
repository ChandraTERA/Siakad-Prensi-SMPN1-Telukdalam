<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle AJAX login request
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Validate request
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
                'remember' => ['boolean'],
            ]);

            // Rate limiting check
            $this->ensureIsNotRateLimited($request);

            // Attempt authentication
            if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey($request));

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'errors' => [
                        'email' => ['The provided credentials are incorrect.']
                    ]
                ], 401);
            }

            // Clear rate limiter
            RateLimiter::clear($this->throttleKey($request));

            // Regenerate session if session is available
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }

            // Get user data
            $user = Auth::user();
            
            // Determine redirect URL based on role
            $redirectUrl = $this->getRedirectUrl($user->role);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'redirect_url' => $redirectUrl
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Login error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle AJAX logout request
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            Auth::guard('web')->logout();
            
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
                'redirect_url' => '/'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle AJAX register request
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Validate request
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa', // Default role for registration
            ]);

            // Fire registered event
            event(new Registered($user));

            // Auto login after registration
            Auth::login($user);

            // Get redirect URL
            $redirectUrl = $this->getRedirectUrl($user->role);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'redirect_url' => $redirectUrl
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check authentication status
     */
    public function checkAuth(Request $request): JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);
        }

        return response()->json([
            'authenticated' => false
        ]);
    }

    /**
     * Ensure the login request is not rate limited
     */
    private function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request
     */
    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl(string $role): string
    {
        return match ($role) {
            'admin' => route('admin.dashboard'),
            'guru' => route('guru.dashboard'),
            'siswa' => route('siswa.dashboard'),
            default => route('dashboard'),
        };
    }
}
