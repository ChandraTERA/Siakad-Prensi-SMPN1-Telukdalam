@extends('layouts.guru-sidebar')

@section('content')
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <a href="{{ route('guru.assignment.show', $assignment) }}" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="text-xl font-semibold text-gray-800">Edit Tugas: {{ $assignment->judul }}</h2>
            </div>
        </div>

        <form action="{{ route('guru.assignment.update', $assignment) }}" method="POST" enctype="multipart/form-data"
            class="p-6">
            @csrf
            @method('PUT')

            <!-- Judul Tugas -->
            <div class="mb-6">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Tugas <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $assignment->judul) }}" required
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
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $assignment->deskripsi) }}</textarea>
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
                    <option value="">Pilih Kelas</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}"
                            {{ old('kelas_id', $assignment->kelas_id) == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama }} ({{ $kelas->siswa_count ?? 0 }} siswa)
                        </option>
                    @endforeach
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
                    <input type="date" name="deadline" id="deadline"
                        value="{{ old('deadline', $assignment->deadline->format('Y-m-d')) }}" required
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
                    <input type="time" name="waktu_deadline" id="waktu_deadline"
                        value="{{ old('waktu_deadline', $assignment->waktu_deadline ? $assignment->waktu_deadline->format('H:i') : '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('waktu_deadline') border-red-500 @enderror">
                    @error('waktu_deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Jika tidak diisi, deadline sampai akhir hari</p>
                </div>
            </div>

            <!-- Current File -->
            @if ($assignment->file_attachment)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Lampiran Saat Ini</label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $assignment->file_name }}</p>
                            <p class="text-xs text-gray-500">File saat ini</p>
                        </div>
                        <a href="{{ route('guru.assignment.download', $assignment) }}"
                            class="text-green-600 hover:text-green-800 p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            <!-- File Attachment -->
            <div class="mb-6">
                <label for="file_attachment" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $assignment->file_attachment ? 'Ganti File Lampiran (Opsional)' : 'File Lampiran (Opsional)' }}
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
                        @if ($assignment->file_attachment)
                            <p class="text-xs text-yellow-600">File baru akan mengganti file yang sudah ada</p>
                        @endif
                    </div>
                </div>
                @error('file_attachment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warning -->
            @if ($assignment->pengumpulanTugas()->count() > 0)
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800">Perhatian!</h4>
                            <p class="text-sm text-yellow-700">Tugas ini sudah memiliki
                                {{ $assignment->pengumpulanTugas()->count() }} pengumpulan dari siswa. Perubahan pada
                                deadline dan file lampiran mungkin akan mempengaruhi siswa yang sudah mengumpulkan.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('guru.assignment.show', $assignment) }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Perbarui Tugas
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

