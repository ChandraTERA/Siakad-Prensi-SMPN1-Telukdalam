@extends('layouts.siswa-sidebar')

@section('page-title', 'Absen Mandiri')
@section('page-description', 'Fitur absen mandiri dengan upload foto')

@section('content')
    <div class="space-y-6">
        @php
            // Check presensi status for current student
            $kelasSiswa = \App\Models\Kelas::whereHas('siswa', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->first();

            $activeSession = null;
            $presensiOpen = false;

            if ($kelasSiswa) {
                $activeSession = \App\Models\PresensiSession::getActiveSession(null, $kelasSiswa->id);
                $presensiOpen = $activeSession && $activeSession->isOpen();
            }
        @endphp

        <!-- Presensi Status Banner -->
        @if ($kelasSiswa)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 {{ $presensiOpen ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 {{ $presensiOpen ? 'text-green-600' : 'text-red-600' }}" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                @if ($presensiOpen)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold {{ $presensiOpen ? 'text-green-800' : 'text-red-800' }}">
                                Status Presensi Kelas {{ $kelasSiswa->nama_kelas }}
                            </h3>
                            <p class="text-sm {{ $presensiOpen ? 'text-green-600' : 'text-red-600' }}">
                                @if ($presensiOpen)
                                    Presensi sedang dibuka - Anda bisa melakukan absen mandiri
                                @else
                                    Presensi ditutup - Tunggu guru membuka sesi presensi
                                @endif
                            </p>
                        </div>
                    </div>
                    @if ($presensiOpen && $activeSession)
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Mulai:</p>
                            <p class="text-sm font-medium text-gray-900">{{ $activeSession->waktu_mulai->format('H:i') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Today's Attendance Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Absen Hari Ini</h3>

            @if ($absenHariIni)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Sudah Absen</p>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($absenHariIni->waktu_absen)->format('H:i') }} WIB</p>
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $absenHariIni->status_badge }}">
                                {{ $absenHariIni->status_text }}
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('siswa.absen-mandiri.show', $absenHariIni->id) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm transition-colors duration-200">
                            Lihat Detail
                        </a>
                        @if ($absenHariIni->status === 'pending')
                            <form action="{{ route('siswa.absen-mandiri.destroy', $absenHariIni->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin membatalkan absen ini?')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm transition-colors duration-200">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                @php
                    // Check if presensi session is open for student's class
$kelasSiswa = \App\Models\Kelas::whereHas('siswa', function ($query) use ($siswa) {
    $query->where('user_id', $siswa->id);
                    })->first();

                    $activeSession = null;
                    $presensiOpen = false;

                    if ($kelasSiswa) {
                        $activeSession = \App\Models\PresensiSession::getActiveSession(null, $kelasSiswa->id);
                        $presensiOpen = $activeSession && $activeSession->isOpen();
                    }
                @endphp

                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    @if ($presensiOpen)
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Absen Hari Ini</h4>
                        <p class="text-gray-500 mb-4">Lakukan absen mandiri dengan mengupload foto Anda</p>
                        <a href="{{ route('siswa.absen-mandiri.create') }}"
                            class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Absen Sekarang
                        </a>
                    @else
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Presensi Belum Dibuka</h4>
                        <p class="text-gray-500 mb-4">Silakan tunggu guru membuka sesi presensi terlebih dahulu</p>
                        <div
                            class="inline-flex items-center bg-gray-400 text-white px-6 py-3 rounded-md cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Presensi Ditutup
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Attendance History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Absen Mandiri</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar pengajuan absen mandiri Anda</p>
            </div>

            @if ($riwayatAbsen->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Disetujui Oleh</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($riwayatAbsen as $absen)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($absen->waktu_absen)->format('H:i') }} WIB
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $absen->status_badge }}">
                                            {{ $absen->status_text }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $absen->guru ? $absen->guru->name : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('siswa.absen-mandiri.show', $absen->id) }}"
                                            class="text-green-600 hover:text-green-900 transition-colors duration-200">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($riwayatAbsen->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $riwayatAbsen->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada riwayat absen mandiri</p>
                </div>
            @endif
        </div>
    </div>
@endsection
