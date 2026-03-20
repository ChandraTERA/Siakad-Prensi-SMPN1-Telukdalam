@extends('layouts.guru-sidebar')

@section('content')
    <div x-data="{
        isModalOpen: false,
        deleteUrl: '',
        taskTitle: '',
        openDeleteModal(url, title) {
            this.deleteUrl = url;
            this.taskTitle = title;
            this.isModalOpen = true;
        }
    }">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('guru.assignment.index') }}" class="text-gray-600 hover:text-gray-800">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7">
                                    </path>
                                </svg>
                            </a>
                            <h2 class="text-xl font-semibold text-gray-800">Detail Tugas</h2>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $assignment->status_badge }}">
                                {{ $assignment->status_text }}
                            </span>
                            @if ($assignment->is_overdue && $assignment->status === 'aktif')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Terlambat
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Assignment Details -->
                        <div class="lg:col-span-2">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $assignment->judul }}</h3>

                            <div class="prose max-w-none mb-6">
                                <div class="text-gray-700 whitespace-pre-line">{{ $assignment->deskripsi }}</div>
                            </div>

                            @if ($assignment->file_attachment)
                                <div class="mb-6">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">File Lampiran</h4>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $assignment->file_name }}</p>
                                            <p class="text-xs text-gray-500">Lampiran tugas</p>
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
                        </div>

                        <!-- Assignment Info -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Informasi Tugas</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Kelas:</span>
                                        <span class="font-medium">{{ $assignment->kelas->nama }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Deadline:</span>
                                        <span class="font-medium">{{ $assignment->deadline_formatted }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Dibuat:</span>
                                        <span class="font-medium">{{ $assignment->created_at->format('d F Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Statistik Pengumpulan</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Total Siswa:</span>
                                        <span class="font-bold text-lg">{{ $totalSiswa }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Sudah Mengumpulkan:</span>
                                        <span class="font-bold text-lg text-blue-600">{{ $totalSubmissions }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Tepat Waktu:</span>
                                        <span class="font-bold text-lg text-green-600">{{ $onTimeSubmissions }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Terlambat:</span>
                                        <span class="font-bold text-lg text-red-600">{{ $lateSubmissions }}</span>
                                    </div>
                                    <div class="pt-2 border-t">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Belum Mengumpulkan:</span>
                                            <span
                                                class="font-bold text-lg text-gray-600">{{ $totalSiswa - $totalSubmissions }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="space-y-2">
                                @if ($assignment->pengumpulanTugas()->count() === 0)
                                    <a href="{{ route('guru.assignment.edit', $assignment) }}"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors duration-200 text-center block">
                                        Edit Tugas
                                    </a>
                                @endif
                                <button type="button"
                                    @click="openDeleteModal('{{ route('guru.assignment.destroy', $assignment) }}', '{{ $assignment->judul }}')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                                    Hapus Tugas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submissions -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Pengumpulan Tugas</h3>
                </div>

                <div class="p-6">
                    @if ($assignment->pengumpulanTugas->count() > 0)
                        <div class="space-y-4">
                            @foreach ($assignment->pengumpulanTugas as $submission)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="font-medium text-gray-900">{{ $submission->siswa->name }}</h4>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $submission->status_badge }}">
                                                    {{ $submission->status_text }}
                                                </span>
                                                @if ($submission->nilai)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Nilai: {{ $submission->nilai }}/100
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-sm text-gray-600 mb-2">
                                                Dikumpulkan: {{ $submission->submitted_at_formatted }}
                                            </p>

                                            @if ($submission->jawaban)
                                                <div class="mb-3">
                                                    <p class="text-sm font-medium text-gray-700 mb-1">Jawaban:</p>
                                                    <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded">
                                                        <div x-data="{ expanded: false }">
                                                            <div x-show="!expanded">
                                                                {{ Str::limit($submission->jawaban, 150) }}
                                                                @if (strlen($submission->jawaban) > 150)
                                                                    <button @click="expanded = true"
                                                                        class="ml-2 text-blue-600 hover:text-blue-800 text-xs font-medium underline">
                                                                        Lihat Detail
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <div x-show="expanded" x-transition>
                                                                <div class="whitespace-pre-wrap">
                                                                    {{ $submission->jawaban }}</div>
                                                                <button @click="expanded = false"
                                                                    class="mt-2 text-blue-600 hover:text-blue-800 text-xs font-medium underline">
                                                                    Sembunyikan
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($submission->file_submission)
                                                <div class="mb-3">
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        <span
                                                            class="text-sm text-gray-600">{{ $submission->file_name }}</span>
                                                        <a href="{{ route('guru.assignment.download-submission', $submission) }}"
                                                            class="text-green-600 hover:text-green-800">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($submission->feedback)
                                                <div class="mb-3">
                                                    <p class="text-sm font-medium text-gray-700 mb-1">Feedback:</p>
                                                    <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded">
                                                        {{ $submission->feedback }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Grading Form -->
                                        <div class="ml-4">
                                            <form action="{{ route('guru.assignment.grade', $submission) }}"
                                                method="POST" class="space-y-2">
                                                @csrf
                                                <div>
                                                    <input type="number" name="nilai" min="0" max="100"
                                                        value="{{ $submission->nilai }}" placeholder="Nilai (0-100)"
                                                        class="w-24 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500">
                                                </div>
                                                <div>
                                                    <textarea name="feedback" rows="2" placeholder="Feedback (opsional)"
                                                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500">{{ $submission->feedback }}</textarea>
                                                </div>
                                                <button type="submit"
                                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                                                    Simpan Nilai
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pengumpulan</h3>
                            <p class="mt-1 text-sm text-gray-500">Siswa belum mengumpulkan tugas ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div @click.away="isModalOpen = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mt-4">Konfirmasi Penghapusan</h3>
                    <p class="text-gray-600 mt-2">Apakah Anda yakin ingin menghapus tugas <span x-text="taskTitle"
                            class="font-semibold text-gray-900"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex justify-center space-x-4 mt-6">
                        <button @click="isModalOpen = false"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <form :action="deleteUrl" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
