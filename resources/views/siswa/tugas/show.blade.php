@extends('layouts.siswa-sidebar')

@section('page-title', 'Detail Tugas')
@section('page-description', $tugas->judul)

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <a href="{{ route('siswa.tugas.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 mb-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Kembali ke Daftar Tugas
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $tugas->judul }}</h1>
                <p class="text-gray-500 mt-1">
                    Oleh: {{ $tugas->guru->name ?? 'Guru' }} &bull; Untuk Kelas: {{ $tugas->kelas->nama_kelas ?? 'Kelas' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Deskripsi Tugas
                        </h3>
                        <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($tugas->deskripsi)) !!}
                        </div>

                        @if ($tugas->file_attachment)
                            <div class="mt-6 border-t pt-5">
                                <p class="text-sm font-medium text-gray-600 mb-2">Lampiran:</p>
                                <a href="#"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-medium rounded-lg hover:bg-indigo-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>{{ $tugas->file_name ?? 'Download File' }}</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($pengumpulan)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-3 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Jawaban Anda
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div
                                    class="md:col-span-1 bg-gray-50 rounded-lg p-6 flex flex-col items-center justify-center text-center">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Nilai</p>
                                    <span class="text-5xl font-bold text-indigo-600">{{ $pengumpulan->nilai ?? '–' }}</span>
                                    <span class="text-gray-500 text-sm mt-1">/ 100</span>
                                </div>
                                <dl class="md:col-span-2 space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Waktu Pengumpulan</dt>
                                        <dd class="mt-1 text-md font-semibold text-gray-900">
                                            {{ $pengumpulan->submitted_at_formatted }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $pengumpulan->status_badge }}">
                                                {{ $pengumpulan->status_text }}
                                            </span>
                                        </dd>
                                    </div>
                                    @if ($pengumpulan->file_submission)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">File Jawaban</dt>
                                            <dd class="mt-1">
                                                <a href="{{ asset('storage/' . $pengumpulan->file_submission) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:underline">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                    {{ $pengumpulan->file_name }}
                                                </a>
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            @if ($pengumpulan->jawaban)
                                <div class="mt-6 border-t pt-5">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Jawaban Teks:</h4>
                                    <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">
                                        {{ $pengumpulan->jawaban }}</div>
                                </div>
                            @endif

                            @if ($pengumpulan->feedback)
                                <div class="mt-6">
                                    <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded-r-lg">
                                        <h4 class="text-sm font-semibold text-indigo-800 flex items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Feedback Guru
                                        </h4>
                                        <p class="text-sm text-indigo-700">{{ $pengumpulan->feedback }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                        <form action="{{ route('siswa.tugas.submit', $tugas->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-3 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Kumpulkan Jawaban
                                </h3>
                                <div class="space-y-5">
                                    <div>
                                        <label for="jawaban" class="block text-sm font-medium text-gray-700 mb-1">Jawaban
                                            Teks</label>
                                        <textarea id="jawaban" name="jawaban" rows="5"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition @error('jawaban') border-red-500 @enderror"
                                            placeholder="Ketik jawaban Anda di sini... (Maksimal 200 kata)">{{ old('jawaban') }}</textarea>
                                        <div class="flex justify-between items-center mt-1">
                                            <p class="text-xs text-gray-500">Maksimal 200 kata</p>
                                            <span id="word-count" class="text-xs text-gray-500">0/200 kata</span>
                                        </div>
                                        @error('jawaban')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="file_submission"
                                            class="block text-sm font-medium text-gray-700 mb-1">Upload File
                                            (Opsional)</label>
                                        <input type="file" id="file_submission" name="file_submission"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition @error('file_submission') ring-1 ring-red-500 rounded-lg p-2 @enderror" />
                                        <p class="mt-1 text-xs text-gray-500">Maksimal: 5MB (PDF, DOCX, JPG, ZIP, etc.)</p>
                                        @error('file_submission')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 border-t rounded-b-xl">
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Kirim Jawaban
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                    <h4 class="text-md font-semibold text-gray-900 flex items-center gap-2 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Batas Waktu
                    </h4>
                    <p class="text-lg font-bold text-gray-800">
                        {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('l, d F Y') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Pukul {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('H:i') }} WIB
                    </p>
                    @php
                        $deadline = \Carbon\Carbon::parse($tugas->deadline);
                        $now = \Carbon\Carbon::now();
                        $isLate = $now->gt($deadline);
                        $diffForHumans = $deadline->diffForHumans(['parts' => 2, 'short' => true]);
                    @endphp
                    <div @class([
                        'mt-3 px-3 py-1.5 text-sm font-medium rounded-lg text-center',
                        'bg-red-100 text-red-800' => $isLate,
                        'bg-green-100 text-green-800' => !$isLate,
                    ])>
                        {{ $isLate ? 'Waktu habis' : 'Sisa waktu ' . $diffForHumans }}
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        // Word counter for textarea
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('jawaban');
            const wordCount = document.getElementById('word-count');

            if (textarea && wordCount) {
                // Function to count words
                function countWords(text) {
                    if (!text.trim()) return 0;
                    return text.trim().split(/\s+/).length;
                }

                // Update counter on input
                textarea.addEventListener('input', function() {
                    const currentWords = countWords(this.value);
                    wordCount.textContent = currentWords + '/200 kata';

                    // Change color when approaching limit
                    if (currentWords > 180) {
                        wordCount.classList.add('text-red-500');
                        wordCount.classList.remove('text-gray-500');
                    } else {
                        wordCount.classList.add('text-gray-500');
                        wordCount.classList.remove('text-red-500');
                    }

                    // Prevent submission if over limit
                    if (currentWords > 200) {
                        this.value = this.value.trim().split(/\s+/).slice(0, 200).join(' ');
                        wordCount.textContent = '200/200 kata';
                        wordCount.classList.add('text-red-500');
                        wordCount.classList.remove('text-gray-500');
                    }
                });

                // Initialize counter
                const initialWords = countWords(textarea.value);
                wordCount.textContent = initialWords + '/200 kata';
            }
        });
    </script>
@endsection
