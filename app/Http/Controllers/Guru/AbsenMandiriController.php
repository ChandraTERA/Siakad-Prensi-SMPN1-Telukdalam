<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsenMandiri;
use App\Models\Kelas;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsenMandiriController extends Controller
{
    public function index($kelasId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($kelasId);

        // Get all absen mandiri for students in this class
        $absenMandiri = AbsenMandiri::whereHas('siswa', function ($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId);
        })
        ->with(['siswa.user', 'guru'])
        ->orderBy('tanggal', 'desc')
        ->orderBy('waktu_absen', 'desc')
        ->paginate(15);

        return view('guru.absen-mandiri.index', compact('kelas', 'absenMandiri'));
    }

    public function show($kelasId, $absenId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($kelasId);

        // Get absen mandiri
        $absenMandiri = AbsenMandiri::whereHas('siswa', function ($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId);
        })
        ->with(['siswa.user', 'guru'])
        ->findOrFail($absenId);

        return view('guru.absen-mandiri.show', compact('kelas', 'absenMandiri'));
    }

    public function updateStatus(Request $request, $kelasId, $absenId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($kelasId);

        // Get absen mandiri
        $absenMandiri = AbsenMandiri::whereHas('siswa', function ($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId);
        })->findOrFail($absenId);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'keterangan' => 'nullable|string|max:500'
        ]);

        // Update status
        $absenMandiri->update([
            'status' => $request->status,
            'guru_id' => Auth::user()->id,
            'waktu_approval' => Carbon::now(),
            'keterangan_guru' => $request->keterangan,
        ]);

        // Send notification to student
        $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
        $message = $request->status === 'approved' 
            ? "Presensi mandiri Anda telah disetujui oleh " . Auth::user()->name . "."
            : "Presensi mandiri Anda ditolak oleh " . Auth::user()->name . ". " . ($request->keterangan ? "Alasan: " . $request->keterangan : "");
        
        // Ensure we have a valid user_id before creating notification
        $userId = $absenMandiri->siswa->user_id;
        if ($userId && $userId > 0) {
            NotificationService::create(
                $userId,
                'presensi_verification',
                'Status Presensi Mandiri',
                $message,
                [
                    'absen_id' => $absenMandiri->id,
                    'status' => $request->status,
                    'guru_name' => Auth::user()->name,
                    'keterangan' => $request->keterangan
                ]
            );
        }
        
        return redirect()->route('guru.absen-mandiri.show', [$kelasId, $absenId])
            ->with('success', "Absen mandiri {$statusText} berhasil!");
    }
}
