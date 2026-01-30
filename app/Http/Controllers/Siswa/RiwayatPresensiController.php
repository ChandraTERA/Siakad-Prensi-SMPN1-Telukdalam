<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatPresensiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Auth::user();
        
        // Get filter parameters
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        
        // Get attendance records for the student
        $riwayatPresensi = Presensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
        
        // Calculate statistics
        $totalPresensi = Presensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();
            
        $hadir = Presensi::where('siswa_id', $siswa->id)
            ->where('status', 'hadir')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();
            
        $sakit = Presensi::where('siswa_id', $siswa->id)
            ->where('status', 'sakit')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();
            
        $izin = Presensi::where('siswa_id', $siswa->id)
            ->where('status', 'izin')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();
            
        $alfa = Presensi::where('siswa_id', $siswa->id)
            ->where('status', 'alfa')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();
        
        $persentaseKehadiran = $totalPresensi > 0 ? round(($hadir / $totalPresensi) * 100, 1) : 0;
        
        // Get available months and years for filter
        $availableMonthsYears = Presensi::where('siswa_id', $siswa->id)
            ->selectRaw('DISTINCT YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        
        return view('siswa.riwayat-presensi', compact(
            'siswa',
            'riwayatPresensi',
            'totalPresensi',
            'hadir',
            'sakit',
            'izin',
            'alfa',
            'persentaseKehadiran',
            'bulan',
            'tahun',
            'availableMonthsYears'
        ));
    }
}