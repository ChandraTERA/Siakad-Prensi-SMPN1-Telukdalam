<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsenMandiri;
use App\Models\PresensiSession;
use App\Models\Kelas;
use App\Models\SchoolLocation;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AbsenMandiriController extends Controller
{
    public function index()
    {
        $siswa = Auth::user();
        
        // Get today's attendance if exists
        $absenHariIni = AbsenMandiri::where('siswa_id', $siswa->id)
            ->where('tanggal', today())
            ->first();
        
        // Get recent attendance history
        $riwayatAbsen = AbsenMandiri::where('siswa_id', $siswa->id)
            ->with('guru')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('siswa.absen-mandiri.index', compact('siswa', 'absenHariIni', 'riwayatAbsen'));
    }
    
    public function create()
    {
        $siswa = Auth::user();
        
        // Check if already submitted attendance for today
        $sudahAbsen = AbsenMandiri::where('siswa_id', $siswa->id)
            ->where('tanggal', today())
            ->exists();
        
        if ($sudahAbsen) {
            return redirect()->route('siswa.absen-mandiri.index')
                ->with('error', 'Anda sudah melakukan absen mandiri hari ini.');
        }
        
        // Check if presensi session is open for student's class
        $kelasSiswa = Kelas::whereHas('siswa', function($query) use ($siswa) {
            $query->where('user_id', $siswa->id);
        })->with('guruPengajar')->first();
        
        if (!$kelasSiswa) {
            return redirect()->route('siswa.absen-mandiri.index')
                ->with('error', 'Anda tidak terdaftar di kelas manapun.');
        }
        
        // Check if there's an active presensi session for this class
        $activeSession = PresensiSession::getActiveSession(null, $kelasSiswa->id);
        
        if (!$activeSession || !$activeSession->isOpen()) {
            return redirect()->route('siswa.absen-mandiri.index')
                ->with('error', 'Presensi belum dibuka oleh guru. Silakan tunggu guru membuka sesi presensi.');
        }
        
        return view('siswa.absen-mandiri.create', compact('siswa', 'activeSession'));
    }
    
    public function store(Request $request)
    {
        $siswa = Auth::user();
        
        // Check if already submitted attendance for today
        $sudahAbsen = AbsenMandiri::where('siswa_id', $siswa->id)
            ->where('tanggal', today())
            ->exists();
        
        if ($sudahAbsen) {
            return redirect()->route('siswa.absen-mandiri.index')
                ->with('error', 'Anda sudah melakukan absen mandiri hari ini.');
        }
        
        // Check if presensi session is open for student's class
        $kelasSiswa = Kelas::whereHas('siswa', function($query) use ($siswa) {
            $query->where('user_id', $siswa->id);
        })->with('guruPengajar')->first();
        
        if (!$kelasSiswa) {
            return redirect()->route('siswa.absen-mandiri.index')
                ->with('error', 'Anda tidak terdaftar di kelas manapun.');
        }
        
        // Check if there's an active presensi session for this class
        $activeSession = PresensiSession::getActiveSession(null, $kelasSiswa->id);
        
        if (!$activeSession || !$activeSession->isOpen()) {
            return redirect()->route('siswa.absen-mandiri.index')
                ->with('error', 'Presensi belum dibuka oleh guru. Silakan tunggu guru membuka sesi presensi.');
        }
        
        $request->validate([
            'foto_absen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
        
        // Validasi GPS jika koordinat diberikan
        $gpsData = $this->validateGpsLocation($request->latitude, $request->longitude);
        
        // Blokir absensi jika di luar radius dan GPS tersedia
        if ($request->latitude && $request->longitude && !$gpsData['is_within_radius']) {
            return redirect()->route('siswa.absen-mandiri.create')
                ->with('error', 'Absensi ditolak! Anda berada di luar radius sekolah yang diizinkan. Jarak: ' . $gpsData['distance_meters'] . ' meter dari sekolah.');
        }
        
        // Upload foto
        $fotoPath = $request->file('foto_absen')->store('absen-mandiri', 'public');
        
        // Create attendance record
        $absenMandiri = AbsenMandiri::create([
            'siswa_id' => $siswa->id,
            'tanggal' => today(),
            'waktu_absen' => now()->format('H:i:s'),
            'foto_absen' => $fotoPath,
            'keterangan' => $request->keterangan,
            'status' => 'pending',
            'latitude' => $gpsData['latitude'],
            'longitude' => $gpsData['longitude'],
            'distance_meters' => $gpsData['distance_meters'],
            'is_within_radius' => $gpsData['is_within_radius'],
            'gps_error' => $gpsData['gps_error'],
        ]);

        // Send notification to all teachers who teach this class
        $guruIds = $kelasSiswa->guruPengajar->pluck('id')->toArray();
        
        // Filter out null values and ensure we have valid guru IDs
        $guruIds = array_filter($guruIds, function($id) {
            return !is_null($id) && $id > 0;
        });
        
        // If no valid guru IDs found, try alternative approach
        if (empty($guruIds)) {
            // Fallback: get all teachers who teach this class via different method
            $guruIds = DB::table('guru_kelas')
                ->where('kelas_id', $kelasSiswa->id)
                ->pluck('user_id')
                ->toArray();
            
            $guruIds = array_filter($guruIds, function($id) {
                return !is_null($id) && $id > 0;
            });
        }
        
        foreach ($guruIds as $guruId) {
            NotificationService::create(
                $guruId,
                'presensi_verification',
                'Presensi Mandiri Baru',
                "Siswa {$siswa->name} telah mengirim presensi mandiri yang perlu diverifikasi.",
                [
                    'absen_id' => $absenMandiri->id,
                    'siswa_name' => $siswa->name,
                    'kelas_name' => $kelasSiswa->nama_kelas
                ]
            );
        }
        
        return redirect()->route('siswa.absen-mandiri.index')
            ->with('success', 'Absen mandiri berhasil dikirim. Menunggu persetujuan guru.');
    }
    
    public function show($id)
    {
        $siswa = Auth::user();
        $absen = AbsenMandiri::where('siswa_id', $siswa->id)
            ->with('guru')
            ->findOrFail($id);
        
        return view('siswa.absen-mandiri.show', compact('siswa', 'absen'));
    }
    
    public function destroy($id)
    {
        $siswa = Auth::user();
        $absen = AbsenMandiri::where('siswa_id', $siswa->id)
            ->where('status', 'pending')
            ->findOrFail($id);
        
        // Delete foto
        if (Storage::disk('public')->exists($absen->foto_absen)) {
            Storage::disk('public')->delete($absen->foto_absen);
        }
        
        $absen->delete();
        
        return redirect()->route('siswa.absen-mandiri.index')
            ->with('success', 'Absen mandiri berhasil dibatalkan.');
    }
    
    /**
     * Check if presensi is open for student's class
     */
    public function checkPresensiStatus()
    {
        $siswa = Auth::user();
        
        // Get student's class
        $kelasSiswa = Kelas::whereHas('siswa', function($query) use ($siswa) {
            $query->where('user_id', $siswa->id);
        })->with('guruPengajar')->first();
        
        if (!$kelasSiswa) {
            return response()->json([
                'is_open' => false,
                'message' => 'Anda tidak terdaftar di kelas manapun'
            ]);
        }
        
        // Check if there's an active presensi session for this class
        $activeSession = PresensiSession::getActiveSession(null, $kelasSiswa->id);
        
        if ($activeSession && $activeSession->isOpen()) {
            return response()->json([
                'is_open' => true,
                'message' => 'Presensi sedang dibuka',
                'session' => [
                    'waktu_mulai' => $activeSession->waktu_mulai->format('H:i'),
                    'auto_close_at' => $activeSession->auto_close_at ? $activeSession->auto_close_at->format('H:i') : null,
                    'keterangan' => $activeSession->keterangan,
                ]
            ]);
        }
        
        return response()->json([
            'is_open' => false,
            'message' => 'Presensi belum dibuka oleh guru'
        ]);
    }

    /**
     * Validasi GPS location terhadap sekolah
     */
    private function validateGpsLocation($latitude, $longitude)
    {
        $gpsData = [
            'latitude' => null,
            'longitude' => null,
            'distance_meters' => null,
            'is_within_radius' => false,
            'gps_error' => null,
        ];

        // Jika koordinat tidak diberikan, skip validasi GPS
        if (!$latitude || !$longitude) {
            $gpsData['gps_error'] = 'Koordinat GPS tidak tersedia';
            return $gpsData;
        }

        // Dapatkan lokasi sekolah aktif
        $schoolLocation = SchoolLocation::getMainLocation();
        
        if (!$schoolLocation) {
            $gpsData['latitude'] = $latitude;
            $gpsData['longitude'] = $longitude;
            $gpsData['gps_error'] = 'Lokasi sekolah belum dikonfigurasi';
            return $gpsData;
        }

        // Hitung jarak dari sekolah
        $distance = SchoolLocation::calculateDistance(
            $schoolLocation->latitude,
            $schoolLocation->longitude,
            $latitude,
            $longitude
        );

        // Validasi apakah berada dalam radius
        $isWithinRadius = $schoolLocation->isWithinRadius($latitude, $longitude);

        $gpsData['latitude'] = $latitude;
        $gpsData['longitude'] = $longitude;
        $gpsData['distance_meters'] = round($distance);
        $gpsData['is_within_radius'] = $isWithinRadius;

        return $gpsData;
    }

    /**
     * API endpoint untuk validasi GPS real-time
     */
    public function validateGps(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $gpsData = $this->validateGpsLocation($request->latitude, $request->longitude);

        return response()->json([
            'success' => true,
            'gps_data' => $gpsData,
            'school_location' => SchoolLocation::getMainLocation() ? [
                'nama_lokasi' => SchoolLocation::getMainLocation()->nama_lokasi,
                'radius_meter' => SchoolLocation::getMainLocation()->radius_meter,
            ] : null,
        ]);
    }
}