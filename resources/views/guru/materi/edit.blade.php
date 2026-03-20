@extends('layouts.guru-sidebar')

@section('page-title', 'Edit Materi')
@section('page-description', 'Edit materi pembelajaran')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Materi</h2>
            <a href="{{ route('guru.materi.show', $materi) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('guru.materi.update', $materi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Materi -->
                    <div class="md:col-span-2">
                        <label for="nama_materi" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Materi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_materi" id="nama_materi"
                            value="{{ old('nama_materi', $materi->nama_materi) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('nama_materi') border-red-500 @enderror"
                            placeholder="Masukkan nama materi">
                        @error('nama_materi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mata Pelajaran -->
                    <div>
                        <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <select name="mapel_id" id="mapel_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('mapel_id') border-red-500 @enderror">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach ($mataPelajarans as $mapel)
                                <option value="{{ $mapel->id }}"
                                    {{ old('mapel_id', $materi->mapel_id) == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select name="kelas_id" id="kelas_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('kelas_id') border-red-500 @enderror">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}"
                                    {{ old('kelas_id', $materi->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} - {{ $k->tingkat }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('deskripsi') border-red-500 @enderror"
                            placeholder="Masukkan deskripsi materi (opsional)">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="md:col-span-2">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            File Materi Baru (Opsional)
                        </label>

                        <!-- Current File Info -->
                        @if ($materi->file_path)
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file text-2xl text-gray-400 mr-3"></i>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $materi->file_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $materi->file_size_formatted }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($materi->file_path) }}" target="_blank"
                                        class="text-sm text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                        <span>Upload file baru</span>
                                        <input id="file" name="file" type="file" class="sr-only"
                                            accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.mp4,.mp3,.zip,.rar"
                                            onchange="showFileName(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, JPG, PNG, GIF, MP4, MP3, ZIP, RAR (Max 10MB)
                                </p>
                                <p class="text-xs text-gray-400">
                                    Kosongkan jika tidak ingin mengganti file
                                </p>
                            </div>
                        </div>
                        <div id="file-name" class="mt-2 text-sm text-gray-600 hidden"></div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('guru.materi.show', $materi) }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showFileName(input) {
            const fileName = input.files[0]?.name;
            const fileNameDiv = document.getElementById('file-name');

            if (fileName) {
                fileNameDiv.textContent = 'File dipilih: ' + fileName;
                fileNameDiv.classList.remove('hidden');
            } else {
                fileNameDiv.classList.add('hidden');
            }
        }
    </script>
@endsection
