@extends('layouts.admin-sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Edit Guru</h2>
                        <a href="{{ route('admin.guru.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $guru->user->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('name') ? 'border-red-500' : '' }}"
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                    </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $guru->user->email) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('email') ? 'border-red-500' : '' }}"
                                        @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                        </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru
                                            <span class="text-sm text-gray-500">(Kosongkan jika tidak ingin
                                                mengubah)</span></label>
                                        <input type="password" name="password" id="password"
                                            placeholder="Masukkan password baru"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('password') ? 'border-red-500' : '' }}">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation"
                                            class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            placeholder="Ulangi password baru"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="mt-1 text-sm text-gray-500">Hanya perlu diisi jika mengubah password</p>
                                    </div>

                                    <div>
                                        <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                                        <input type="text" name="nip" id="nip"
                                            value="{{ old('nip', $guru->nip) }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('nip') ? 'border-red-500' : '' }}"
                                            @error('nip')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                            @error('nip')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                            </div>

                                        <div>
                                            <label for="jabatan"
                                                class="block text-sm font-medium text-gray-700">Jabatan</label>
                                            <input type="text" name="jabatan" id="jabatan"
                                                value="{{ old('jabatan', $guru->jabatan) }}" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('jabatan') ? 'border-red-500' : '' }}"
                                                @error('jabatan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                                </div>

                                            <div>
                                                <label for="no_telepon" class="block text-sm font-medium text-gray-700">No.
                                                    Telepon</label>
                                                <input type="text" name="no_telepon" id="no_telepon"
                                                    value="{{ old('no_telepon', $guru->no_telepon) }}" required
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('no_telepon') ? 'border-red-500' : '' }}"
                                                    @error('no_telepon')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                                    </div>

                                                <div>
                                                    <label for="alamat"
                                                        class="block text-sm font-medium text-gray-700">Alamat</label>
                                                    <textarea name="alamat" id="alamat" rows="3" required
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('alamat') ? 'border-red-500' : '' }}">{{ old('alamat', $guru->alamat) }}</textarea>
                                                    @error('alamat')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mt-6">
                                                <button type="submit"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    Update
                                                </button>
                                                <a href="{{ route('admin.guru.index') }}"
                                                    class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                                    Batal
                                                </a>
                                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password_confirmation');

            // Clear confirmation field when password field is cleared
            passwordField.addEventListener('input', function() {
                if (this.value === '') {
                    confirmPasswordField.value = '';
                    confirmPasswordField.disabled = true;
                    confirmPasswordField.style.backgroundColor = '#f9fafb';
                } else {
                    confirmPasswordField.disabled = false;
                    confirmPasswordField.style.backgroundColor = '';
                }
            });

            // Initially disable confirmation field if password is empty
            if (passwordField.value === '') {
                confirmPasswordField.disabled = true;
                confirmPasswordField.style.backgroundColor = '#f9fafb';
            }
        });
    </script>
@endsection
