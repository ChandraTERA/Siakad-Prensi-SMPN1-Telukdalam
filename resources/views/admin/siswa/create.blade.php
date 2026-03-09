@extends('layouts.admin-sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Tambah Siswa</h2>
                        <a href="{{ route('admin.siswa.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.siswa.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('name') ? 'border-red-500' : '' }}">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('email') ? 'border-red-500' : '' }}">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" id="password" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('password') ? 'border-red-500' : '' }}">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="nis" class="block text-sm font-medium text-gray-700">NIS</label>
                                <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('nis') ? 'border-red-500' : '' }}">
                                @error('nis')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                                <select name="kelas_id" id="kelas_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('kelas_id') ? 'border-red-500' : '' }}">
                                    <option value="">Pilih Kelas (Opsional)</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }} - {{ $kelas->tingkat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nama_orang_tua" class="block text-sm font-medium text-gray-700">Nama Orang
                                    Tua</label>
                                <input type="text" name="nama_orang_tua" id="nama_orang_tua"
                                    value="{{ old('nama_orang_tua') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('nama_orang_tua') ? 'border-red-500' : '' }}"
                                    @error('nama_orang_tua')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                    </div>

                                <div>
                                    <label for="kontak_orang_tua" class="block text-sm font-medium text-gray-700">Kontak
                                        Orang
                                        Tua</label>
                                    <input type="text" name="kontak_orang_tua" id="kontak_orang_tua"
                                        value="{{ old('kontak_orang_tua') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('kontak_orang_tua') ? 'border-red-500' : '' }}">
                                    @error('kontak_orang_tua')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat
                                        Siswa</label>
                                    <textarea name="alamat" id="alamat" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('alamat') ? 'border-red-500' : '' }}">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan
                                </button>
                                <a href="{{ route('admin.siswa.index') }}"
                                    class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Batal
                                </a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
