@extends('layouts.siswa-sidebar')

@section('page-title', 'Daftar Tugas')
@section('page-description', 'Berikut adalah daftar tugas yang perlu Anda kerjakan.')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Tugas Anda</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Tugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($tugasList as $tugas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $tugas->judul }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('l, d F Y - H:i') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $pengumpulan = $tugas->pengumpulanTugas->first();
                                @endphp
                                @if ($pengumpulan)
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full {{ $pengumpulan->status_badge }}">
                                        {{ $pengumpulan->status_text }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Belum Dikerjakan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('siswa.tugas.show', $tugas->id) }}"
                                    class="btn btn-sm btn-primary btn-outline">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                Belum ada tugas yang diberikan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tugasList->hasPages())
            <div class="p-6 border-t">{{ $tugasList->links() }}</div>
        @endif
    </div>
@endsection
