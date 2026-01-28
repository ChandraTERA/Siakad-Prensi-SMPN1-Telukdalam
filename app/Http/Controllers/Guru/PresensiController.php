<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\PresensiSession;
use App\Models\Kelas;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Display list of classes for attendance input
     */
    public function index()
    {
        $kelasList = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->withCount('siswa')->get();

        return view('guru.presensi.index', compact('kelasList'));
    }

    /**
     * Show attendance input form for specific class
     */
    public function create(Request $request, $kelasId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($kelasId);

        // Get search parameter
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        // Get students with pagination and search
        $siswaQuery = $kelas->siswa()->with('siswa');

        if ($search) {
            $siswaQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('siswa', function($q) use ($search) {
                          $q->where('nis', 'like', "%{$search}%");
                      });
            });
        }

        $siswaPaginated = $siswaQuery->orderBy('name')->paginate($perPage);
        $siswaIds = $siswaPaginated->pluck('id')->toArray();

        $tanggal = Carbon::today();

        // Check if attendance already exists for today
        $existingPresensi = Presensi::where('kelas_id', $kelasId)
            ->where('guru_id', Auth::user()->id)
            ->where('tanggal', $tanggal)
            ->whereIn('siswa_id', $siswaIds)
            ->with('siswa')
            ->get()
            ->keyBy('siswa_id');

        return view('guru.presensi.create', compact('kelas', 'tanggal', 'existingPresensi', 'siswaPaginated', 'search', 'perPage'));
    }

    /**
     * Store attendance data
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'presensi' => 'required|array',
            'presensi.*.siswa_id' => 'required|exists:users,id',
            'presensi.*.status' => 'required|in:hadir,izin,sakit,alpa',
            'presensi.*.keterangan' => 'nullable|string|max:255',
        ]);

        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($request->kelas_id);

        $tanggal = Carbon::parse($request->tanggal);
        $guruId = Auth::user()->id;

        // Delete existing attendance for today (if updating)
        Presensi::where('kelas_id', $request->kelas_id)
            ->where('guru_id', $guruId)
            ->where('tanggal', $tanggal)
            ->delete();

        // Insert new attendance records
        foreach ($request->presensi as $presensiData) {
            Presensi::create([
                'siswa_id' => $presensiData['siswa_id'],
                'guru_id' => $guruId,
                'kelas_id' => $request->kelas_id,
                'tanggal' => $tanggal,
                'status' => $presensiData['status'],
                'keterangan' => $presensiData['keterangan'] ?? null,
            ]);
        }

        return redirect()->route('guru.presensi.rekap', $request->kelas_id)
            ->with('success', 'Presensi berhasil disimpan!');
    }

    /**
     * Show attendance recap for specific class
     */
    public function rekap(Request $request, $kelasId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($kelasId);

        // Get search parameter
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        // Get students with pagination and search
        $siswaQuery = $kelas->siswa()->with('siswa');

        if ($search) {
            $siswaQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('siswa', function($q) use ($search) {
                          $q->where('nis', 'like', "%{$search}%");
                      });
            });
        }

        $siswaPaginated = $siswaQuery->orderBy('name')->paginate($perPage);
        $siswaIds = $siswaPaginated->pluck('id')->toArray();

        // Get attendance data for current month
        $currentMonth = Carbon::now()->format('Y-m');
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = $startDate->copy()->endOfMonth();

        $presensiData = Presensi::where('kelas_id', $kelasId)
            ->where('guru_id', Auth::user()->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereIn('siswa_id', $siswaIds)
            ->with('siswa')
            ->orderBy('tanggal')
            ->get();

        // Group by student and date
        $rekapData = [];
        $tanggalList = [];

        foreach ($presensiData as $presensi) {
            $siswaId = $presensi->siswa_id;
            $tanggal = $presensi->tanggal->format('Y-m-d');

            if (!isset($rekapData[$siswaId])) {
                $rekapData[$siswaId] = [
                    'siswa' => $presensi->siswa,
                    'presensi' => [],
                    'summary' => [
                        'hadir' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                        'alpa' => 0,
                    ]
                ];
            }

            $rekapData[$siswaId]['presensi'][$tanggal] = $presensi;
            $rekapData[$siswaId]['summary'][$presensi->status]++;

            if (!in_array($tanggal, $tanggalList)) {
                $tanggalList[] = $tanggal;
            }
        }

        // Add students without attendance data
        foreach ($siswaPaginated as $siswa) {
            if (!isset($rekapData[$siswa->id])) {
                $rekapData[$siswa->id] = [
                    'siswa' => $siswa,
                    'presensi' => [],
                    'summary' => [
                        'hadir' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                        'alpa' => 0,
                    ]
                ];
            }
        }

        sort($tanggalList);

        return view('guru.presensi.rekap', compact('kelas', 'rekapData', 'tanggalList', 'startDate', 'endDate', 'siswaPaginated', 'search', 'perPage'));
    }

    /**
     * Export attendance recap to CSV
     */
    public function exportRekap($kelasId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->with('siswa')->findOrFail($kelasId);

        // Get attendance data for current month
        $currentMonth = Carbon::now()->format('Y-m');
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = $startDate->copy()->endOfMonth();

        $presensiData = Presensi::where('kelas_id', $kelasId)
            ->where('guru_id', Auth::user()->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with('siswa')
            ->orderBy('tanggal')
            ->get();

        // Generate CSV content
        $csvContent = "Rekap Presensi - {$kelas->nama_kelas} - {$startDate->format('F Y')}\n\n";

        // Headers
        $headers = ['Nama Siswa', 'NIS'];
        $tanggalList = [];

        foreach ($presensiData as $presensi) {
            $tanggal = $presensi->tanggal->format('Y-m-d');
            if (!in_array($tanggal, $tanggalList)) {
                $tanggalList[] = $tanggal;
                $headers[] = Carbon::parse($tanggal)->format('d/m');
            }
        }

        $headers = array_merge($headers, ['Total Hadir', 'Total Izin', 'Total Sakit', 'Total Alpa', 'Persentase Kehadiran']);
        $csvContent .= implode(',', $headers) . "\n";

        // Group by student
        $rekapData = [];
        foreach ($presensiData as $presensi) {
            $siswaId = $presensi->siswa_id;
            $tanggal = $presensi->tanggal->format('Y-m-d');

            if (!isset($rekapData[$siswaId])) {
                $rekapData[$siswaId] = [
                    'siswa' => $presensi->siswa,
                    'presensi' => [],
                    'summary' => [
                        'hadir' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                        'alpa' => 0,
                    ]
                ];
            }

            $rekapData[$siswaId]['presensi'][$tanggal] = $presensi;
            $rekapData[$siswaId]['summary'][$presensi->status]++;
        }

        // Add data rows
        foreach ($rekapData as $data) {
            $row = [
                $data['siswa']->name,
                $data['siswa']->siswa->nis ?? 'N/A'
            ];

            foreach ($tanggalList as $tanggal) {
                if (isset($data['presensi'][$tanggal])) {
                    $status = $data['presensi'][$tanggal]->status;
                    $row[] = strtoupper(substr($status, 0, 1));
                } else {
                    $row[] = '-';
                }
            }

            $total = array_sum($data['summary']);
            $attendanceRate = $total > 0 ? round(($data['summary']['hadir'] / $total) * 100, 2) : 0;

            $row = array_merge($row, [
                $data['summary']['hadir'],
                $data['summary']['izin'],
                $data['summary']['sakit'],
                $data['summary']['alpa'],
                $attendanceRate . '%'
            ]);

            $csvContent .= implode(',', $row) . "\n";
        }

        // Add summary row
        $totalHadir = collect($rekapData)->sum('summary.hadir');
        $totalIzin = collect($rekapData)->sum('summary.izin');
        $totalSakit = collect($rekapData)->sum('summary.sakit');
        $totalAlpa = collect($rekapData)->sum('summary.alpa');
        $totalAll = $totalHadir + $totalIzin + $totalSakit + $totalAlpa;
        $overallRate = $totalAll > 0 ? round(($totalHadir / $totalAll) * 100, 2) : 0;

        $csvContent .= "\nTotal Keseluruhan," . str_repeat(',', count($tanggalList) + 1) . "{$totalHadir},{$totalIzin},{$totalSakit},{$totalAlpa},{$overallRate}%\n";

        $filename = "rekap_presensi_{$kelas->nama_kelas}_{$startDate->format('Y_m')}.csv";

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Show students data that guru teaches
     */
    public function siswa($kelasId = null)
    {
        if ($kelasId) {
            // Show students from specific class
            $kelas = Kelas::whereHas('guruPengajar', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->with(['siswa' => function($query) {
                $query->with('siswa')->orderBy('name');
            }])->findOrFail($kelasId);

            return view('guru.siswa.show', compact('kelas'));
        } else {
            // Show all classes and their students
            $kelasList = Kelas::whereHas('guruPengajar', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->with(['siswa' => function($query) {
                $query->with('siswa')->orderBy('name');
            }])->withCount('siswa')->get();

            return view('guru.siswa.index', compact('kelasList'));
        }
    }

    /**
     * Open presensi session for a class
     */
    public function openSession(Request $request, $kelasId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($kelasId);

        // Check if there's already an active session
        $activeSession = PresensiSession::getActiveSession(Auth::user()->id, $kelasId);

        if ($activeSession) {
            return redirect()->route('guru.presensi.index')
                ->with('error', 'Sesi presensi sudah dibuka sebelumnya untuk kelas ' . $kelas->nama_kelas);
        }

        // Create new session with default settings
        $session = PresensiSession::createSession(
            Auth::user()->id,
            $kelasId,
            Carbon::today(),
            null // No auto-close by default
        );

        // Send notification to all students in the class
        NotificationService::createForClassStudents(
            $kelasId,
            'presensi_open',
            'Presensi Dibuka',
            "Presensi untuk kelas {$kelas->nama_kelas} telah dibuka oleh " . Auth::user()->name . ". Silakan lakukan presensi mandiri.",
            ['kelas_name' => $kelas->nama_kelas, 'guru_name' => Auth::user()->name]
        );

        return redirect()->route('guru.presensi.index')
            ->with('success', 'Sesi presensi berhasil dibuka untuk kelas ' . $kelas->nama_kelas . '!');
    }

    /**
     * Close presensi session for a class
     */
    public function closeSession($kelasId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($kelasId);

        // Find active session
        $activeSession = PresensiSession::getActiveSession(Auth::user()->id, $kelasId);

        if (!$activeSession) {
            return redirect()->route('guru.presensi.index')
                ->with('error', 'Tidak ada sesi presensi yang aktif untuk kelas ' . $kelas->nama_kelas);
        }

        // Close the session
        $activeSession->update([
            'status' => PresensiSession::STATUS_TUTUP,
            'waktu_selesai' => Carbon::now(),
        ]);

        // Send notification to all students in the class
        NotificationService::createForClassStudents(
            $kelasId,
            'presensi_close',
            'Presensi Ditutup',
            "Presensi untuk kelas {$kelas->nama_kelas} telah ditutup oleh " . Auth::user()->name . ". Terima kasih atas partisipasinya.",
            ['kelas_name' => $kelas->nama_kelas, 'guru_name' => Auth::user()->name]
        );

        return redirect()->route('guru.presensi.index')
            ->with('success', 'Sesi presensi berhasil ditutup untuk kelas ' . $kelas->nama_kelas . '!');
    }

    /**
     * Get current session status for a class
     */
    public function getSessionStatus($kelasId)
    {
        // Verify guru teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($kelasId);

        $activeSession = PresensiSession::getActiveSession(Auth::user()->id, $kelasId);

        if ($activeSession) {
            return response()->json([
                'is_open' => true,
                'session' => [
                    'id' => $activeSession->id,
                    'waktu_mulai' => $activeSession->waktu_mulai->format('H:i'),
                    'auto_close_at' => $activeSession->auto_close_at ? $activeSession->auto_close_at->format('H:i') : null,
                    'keterangan' => $activeSession->keterangan,
                ]
            ]);
        }

        return response()->json([
            'is_open' => false,
            'session' => null
        ]);
    }

    /**
     * Check if presensi is open for students
     */
    public function checkPresensiOpen($kelasId)
    {
        $activeSession = PresensiSession::getActiveSession(Auth::user()->id, $kelasId);

        return response()->json([
            'is_open' => $activeSession ? $activeSession->isOpen() : false,
            'message' => $activeSession && $activeSession->isOpen()
                ? 'Presensi sedang dibuka'
                : 'Presensi sedang ditutup'
        ]);
    }
}
