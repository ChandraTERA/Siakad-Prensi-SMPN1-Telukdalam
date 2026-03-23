@extends('layouts.siswa-sidebar')

@section('page-title', 'Statistik Download')
@section('page-description', 'Lihat statistik download materi')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Statistik Download</h2>
            <a href="{{ route('siswa.materi.show', $materi) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Detail
            </a>
        </div>

        <!-- Materi Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $materi->nama_materi }}</h3>
                    <div class="flex items-center space-x-4 mt-2">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $materi->mataPelajaran->nama_mapel }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $materi->kelas->nama_kelas }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600">{{ $downloads->count() }}</div>
                    <div class="text-sm text-gray-500">Total Downloads</div>
                </div>
            </div>
        </div>

        @if ($downloads->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Riwayat Download</h3>
                    <p class="text-sm text-gray-500">Daftar siswa yang telah mendownload materi ini</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Siswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Download
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($downloads as $download)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $download->siswa->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $download->siswa->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $download->downloaded_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $download->downloaded_at->format('H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-download text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada download</h3>
                <p class="text-gray-500">Belum ada siswa yang mendownload materi ini.</p>
            </div>
        @endif
    </div>
@endsection
