<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kelas::with(['waliKelas', 'guruPengajar'])
            ->withCount(['siswa', 'guruPengajar']);
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_kelas', 'like', "%{$searchTerm}%")
                  ->orWhere('tingkat', 'like', "%{$searchTerm}%")
                  ->orWhereHas('waliKelas', function($subQ) use ($searchTerm) {
                      $subQ->where('name', 'like', "%{$searchTerm}%")
                           ->orWhere('email', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('guruPengajar', function($subQ) use ($searchTerm) {
                      $subQ->where('name', 'like', "%{$searchTerm}%")
                           ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        $kelasList = $query->latest()->paginate(10)->withQueryString();
            
        // Calculate summary statistics
        $totalGuru = User::where('role', 'guru')->count();
        $totalGuruPengajar = $kelasList->sum('guru_pengajar_count');
        $kelasDenganGuru = $kelasList->where('guru_pengajar_count', '>', 0)->count();
        
        return view('admin.kelas.index', compact('kelasList', 'totalGuru', 'totalGuruPengajar', 'kelasDenganGuru'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = User::where('role', 'guru')->orderBy('name')->get();
        return view('admin.kelas.create', compact('gurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255', 'unique:kelas'],
            'tingkat' => ['required', 'string', 'max:255'],
            'wali_kelas_id' => ['required', 'exists:users,id'],
        ]);
        
        $kelas = Kelas::create($validated);
        
        // Create notification for all teachers about new class
        NotificationService::createForAllTeachers(
            'kelas_baru',
            'Kelas Baru Dibuat',
            "Kelas baru '{$kelas->nama_kelas}' telah dibuat oleh admin.",
            ['kelas_id' => $kelas->id, 'kelas_name' => $kelas->nama_kelas]
        );
        
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        $kelas->load(['waliKelas', 'siswa']);
        return view('admin.kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        $gurus = User::where('role', 'guru')->orderBy('name')->get();
        return view('admin.kelas.edit', compact('kelas', 'gurus')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255', Rule::unique('kelas')->ignore($kelas->id)],
            'tingkat' => ['required', 'string', 'max:255'],
            'wali_kelas_id' => ['required', 'exists:users,id'],
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Jika kelas masih memiliki siswa, jangan hapus
        if ($kelas->siswa()->count() > 0) {
            return redirect()->route('admin.kelas.index')->with('error', 'Tidak bisa menghapus kelas yang masih memiliki siswa.');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }

    public function kelolaGuru(Kelas $kelas){
        $semuaGuru = User::where('role', 'guru')->orderBy('name')->get();
        $guruTerpilihIds = $kelas->guruPengajar()->pluck('user_id')->toArray();
         return view('admin.kelas.kelola-guru', [
            'kelas' => $kelas,
            'semuaGuru' => $semuaGuru,
            'guruTerpilihIds' => $guruTerpilihIds,
        ]);
    }

    public function syncGuru(Request $request, Kelas $kelas){
        $request->validate([
            'guru_ids' => 'required|array',
            'guru_ids.*' => 'required|exists:users,id',
        ]);

        $kelas->guruPengajar()->sync($request->guru_ids);

        return redirect()->route('admin.kelas.index')->with('success', 'Guru pengajar berhasil diupdate untuk kelas ' . $kelas->nama_kelas . '.');
    }

    /**
     * Export data guru per kelas to CSV
     */
    public function exportGuruPerKelas()
    {
        $kelasList = Kelas::with(['waliKelas', 'guruPengajar'])
            ->withCount(['siswa', 'guruPengajar'])
            ->orderBy('nama_kelas')
            ->get();

        // Generate CSV content
        $csvContent = "Laporan Guru Pengajar per Kelas - " . now()->format('d/m/Y') . "\n\n";
        
        // Headers
        $headers = ['No', 'Nama Kelas', 'Tingkat', 'Wali Kelas', 'Jumlah Siswa', 'Jumlah Guru Pengajar', 'Daftar Guru Pengajar'];
        $csvContent .= implode(',', $headers) . "\n";

        // Data rows
        foreach ($kelasList as $index => $kelas) {
            $guruNames = $kelas->guruPengajar->pluck('name')->join('; ');
            
            $row = [
                $index + 1,
                $kelas->nama_kelas,
                $kelas->tingkat,
                $kelas->waliKelas->name ?? '-',
                $kelas->siswa_count,
                $kelas->guru_pengajar_count,
                $guruNames ?: '-'
            ];
            
            $csvContent .= implode(',', $row) . "\n";
        }

        // Summary
        $csvContent .= "\n";
        $csvContent .= "Ringkasan,\n";
        $csvContent .= "Total Kelas," . $kelasList->count() . "\n";
        $csvContent .= "Total Guru Pengajar," . $kelasList->sum('guru_pengajar_count') . "\n";
        $csvContent .= "Kelas dengan Guru," . $kelasList->where('guru_pengajar_count', '>', 0)->count() . "\n";
        $csvContent .= "Kelas tanpa Guru," . $kelasList->where('guru_pengajar_count', 0)->count() . "\n";

        $filename = "laporan_guru_per_kelas_" . now()->format('Y_m_d') . ".csv";
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Kelola semua guru di semua kelas
     */
    public function kelolaSemuaGuru()
    {
        $kelasList = Kelas::withCount('guruPengajar')->get();
        $semuaGuru = User::where('role', 'guru')->get();
        
        return view('admin.kelas.kelola-semua-guru', compact('kelasList', 'semuaGuru'));
    }
}