@extends('layouts.guru-sidebar')

@section('content')
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <a href="{{ route('guru.assignment.index') }}" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="text-xl font-semibold text-gray-800">Buat Tugas Baru</h2>
            </div>
        </div>

        <form action="{{ route('guru.assignment.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <!-- Judul Tugas -->
            <div class="mb-6">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Tugas <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Tugas <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="5" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kelas -->
            <div class="mb-6">
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Kelas <span class="text-red-500">*</span>
                </label>
                <select name="kelas_id" id="kelas_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('kelas_id') border-red-500 @enderror">
                    <option value="">-- Pilih Kelas --</option>
                    @forelse ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} ({{ $kelas->siswa_data_count ?? 0 }} siswa)
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada kelas yang tersedia</option>
                    @endforelse
                </select>
                @error('kelas_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deadline -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Deadline <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}" required
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('deadline') border-red-500 @enderror">
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="waktu_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Deadline (Opsional)
                    </label>
                    <input type="time" name="waktu_deadline" id="waktu_deadline" value="{{ old('waktu_deadline') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('waktu_deadline') border-red-500 @enderror">
                    @error('waktu_deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Jika tidak diisi, deadline sampai akhir hari</p>
                </div>
            </div>

            <!-- File Attachment -->
            <div class="mb-6">
                <label for="file_attachment" class="block text-sm font-medium text-gray-700 mb-2">
                    File Lampiran (Opsional)
                </label>
                <div
                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-green-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                            viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="file_attachment"
                                class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                <span>Upload file</span>
                                <input id="file_attachment" name="file_attachment" type="file" class="sr-only"
                                    accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, TXT, JPG, PNG sampai 10MB</p>
                    </div>
                </div>
                @error('file_attachment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructions -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="text-sm font-medium text-blue-900 mb-2">Petunjuk:</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Pastikan judul tugas jelas dan deskriptif</li>
                    <li>• Berikan instruksi yang detail dalam deskripsi</li>
                    <li>• Tentukan deadline yang realistis untuk siswa</li>
                    <li>• File lampiran dapat berupa soal, panduan, atau materi pendukung</li>
                </ul>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('guru.assignment.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Buat Tugas
                </button>
            </div>
        </form>
    </div>

    <script>
        // File upload preview
        document.getElementById('file_attachment').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const label = e.target.parentElement.querySelector('span');
                label.textContent = `${fileName} (${fileSize}MB)`;
            }
        });

        // Set minimum date to tomorrow
        document.getElementById('deadline').min = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    </script>
@endsection
