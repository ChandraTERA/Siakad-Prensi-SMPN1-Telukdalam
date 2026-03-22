@extends('layouts.app')

@section('page-title', 'Profile')
@section('page-description', 'Kelola informasi profil Anda')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="relative inline-block">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    <div
                        class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center border-4 border-white">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ Auth::user()->name }}</h1>
                <p class="text-gray-600 mb-1">{{ Auth::user()->email }}</p>
                <div
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if (Auth::user()->role === 'admin') bg-red-100 text-red-800
                @elseif(Auth::user()->role === 'guru') bg-blue-100 text-blue-800
                @else bg-green-100 text-green-800 @endif">
                    <i
                        class="fas 
                    @if (Auth::user()->role === 'admin') fa-crown
                    @elseif(Auth::user()->role === 'guru') fa-chalkboard-teacher
                    @else fa-graduation-cap @endif mr-2"></i>
                    {{ ucfirst(Auth::user()->role) }}
                </div>
            </div>

            <!-- Profile Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profile Information Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                        <div class="flex items-center">
                            <i class="fas fa-user-edit text-white text-xl mr-3"></i>
                            <h2 class="text-xl font-semibold text-white">Informasi Profil</h2>
                        </div>
                    </div>

                    <div class="p-6">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-2 text-gray-500"></i>Nama Lengkap
                                    </label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $user->name) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                                        placeholder="Masukkan nama lengkap Anda" required>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-envelope mr-2 text-gray-500"></i>Email
                                    </label>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $user->email) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                        placeholder="Masukkan email Anda" required>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                            <div>
                                                <p class="text-sm text-yellow-800 font-medium">Email belum diverifikasi</p>
                                                <p class="text-sm text-yellow-700 mt-1">Silakan verifikasi email Anda untuk
                                                    keamanan akun.</p>
                                                <form id="send-verification" method="post"
                                                    action="{{ route('verification.send') }}" class="mt-2">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-sm text-yellow-600 hover:text-yellow-800 underline">
                                                        Kirim ulang email verifikasi
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <button type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                                        class="flex items-center text-green-600">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span class="text-sm font-medium">Berhasil disimpan!</span>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Update Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                        <div class="flex items-center">
                            <i class="fas fa-lock text-white text-xl mr-3"></i>
                            <h2 class="text-xl font-semibold text-white">Keamanan Akun</h2>
                        </div>
                    </div>

                    <div class="p-6">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div class="space-y-4">
                                <div>
                                    <label for="update_password_current_password"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-key mr-2 text-gray-500"></i>Password Saat Ini
                                    </label>
                                    <input type="password" id="update_password_current_password" name="current_password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('current_password', 'updatePassword') border-red-500 @enderror"
                                        placeholder="Masukkan password saat ini" autocomplete="current-password">
                                    @error('current_password', 'updatePassword')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="update_password_password"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-2 text-gray-500"></i>Password Baru
                                    </label>
                                    <input type="password" id="update_password_password" name="password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('password', 'updatePassword') border-red-500 @enderror"
                                        placeholder="Masukkan password baru" autocomplete="new-password">
                                    @error('password', 'updatePassword')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="update_password_password_confirmation"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-2 text-gray-500"></i>Konfirmasi Password
                                    </label>
                                    <input type="password" id="update_password_password_confirmation"
                                        name="password_confirmation"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Konfirmasi password baru" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <button type="submit"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg font-medium hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-save mr-2"></i>Update Password
                                </button>

                                @if (session('status') === 'password-updated')
                                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                                        class="flex items-center text-green-600">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span class="text-sm font-medium">Password berhasil diupdate!</span>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Account Actions Card -->
            <div class="mt-6 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-white text-xl mr-3"></i>
                        <h2 class="text-xl font-semibold text-white">Aksi Akun</h2>
                    </div>
                </div>

                <div class="p-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-red-600 mt-1 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">Hapus Akun</h3>
                                <p class="text-sm text-red-700 mt-1">
                                    Setelah akun Anda dihapus, semua data dan sumber daya akan dihapus secara permanen.
                                    Sebelum menghapus akun, silakan unduh data yang ingin Anda simpan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button type="button" x-data=""
                        x-on:click="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-3 rounded-lg font-medium hover:from-red-600 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-trash mr-2"></i>Hapus Akun
                    </button>
                </div>
            </div>

            <!-- Back to Dashboard Button -->
            <div class="text-center mt-8">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Delete Account Modal -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Konfirmasi Hapus Akun</h2>
                        <p class="text-sm text-gray-600">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>

                <p class="text-gray-700 mb-6">
                    Apakah Anda yakin ingin menghapus akun Anda? Setelah akun dihapus, semua data dan sumber daya akan
                    dihapus secara permanen.
                    Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.
                </p>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-key mr-2 text-gray-500"></i>Password
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('password', 'userDeletion') border-red-500 @enderror"
                            placeholder="Masukkan password untuk konfirmasi" required>
                        @error('password', 'userDeletion')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-400 transition-colors">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush
@endsection
