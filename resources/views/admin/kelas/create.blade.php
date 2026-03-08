@extends('layouts.admin-sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Tambah Kelas</h2>
                        <a href="{{ route('admin.kelas.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.kelas.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}"
                                    required placeholder="Contoh: X IPA 1, XI IPS 2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nama_kelas') border-red-500 @enderror">
                                @error('nama_kelas')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tingkat" class="block text-sm font-medium text-gray-700">Tingkat</label>
                                <select name="tingkat" id="tingkat" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tingkat') border-red-500 @enderror">
                                    <option value="">Pilih Tingkat</option>
                                    <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                    <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                    <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>Kelas XII
                                    </option>
                                </select>
                                @error('tingkat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700">Wali
                                    Kelas</label>
                                <select name="wali_kelas_id" id="wali_kelas_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('wali_kelas_id') border-red-500 @enderror">
                                    <option value="">Pilih Wali Kelas</option>
                                    @foreach ($gurus as $guru)
                                        <option value="{{ $guru->id }}"
                                            {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('wali_kelas_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Pilih guru yang akan menjadi wali kelas ini</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan
                                </button>
                                <a href="{{ route('admin.kelas.index') }}"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Info Card -->
                    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Nama kelas harus unik dan tidak boleh sama dengan kelas lain</li>
                                        <li>Tingkat menentukan level pendidikan (X, XI, XII)</li>
                                        <li>Setiap kelas harus memiliki wali kelas dari daftar guru yang tersedia</li>
                                        <li>Satu guru hanya bisa menjadi wali kelas untuk satu kelas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
