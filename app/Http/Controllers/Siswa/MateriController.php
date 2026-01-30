<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\MateriDownload;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Materi::with(['mataPelajaran', 'kelas', 'guru'])
            ->where('kelas_id', Auth::user()->siswa->kelas_id);

        // Filter by mata pelajaran
        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('nama_materi', 'like', '%' . $request->search . '%');
        }

        $materis = $query->orderBy('created_at', 'desc')->paginate(12);
        $mataPelajarans = MataPelajaran::all();

        return view('siswa.materi.index', compact('materis', 'mataPelajarans'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Materi $materi)
    {
        // Pastikan siswa bisa mengakses materi untuk kelasnya
        if ($materi->kelas_id !== Auth::user()->siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $materi->load(['mataPelajaran', 'kelas', 'guru']);
        
        return view('siswa.materi.show', compact('materi'));
    }

    /**
     * Download the specified resource.
     */
    public function download(Materi $materi)
    {
        // Pastikan siswa bisa mengakses materi untuk kelasnya
        if ($materi->kelas_id !== Auth::user()->siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        // Cek apakah file ada
        if (!$materi->file_path || !Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Record download
        MateriDownload::create([
            'materi_id' => $materi->id,
            'siswa_id' => Auth::id(),
            'downloaded_at' => now(),
        ]);

        return response()->download(storage_path('app/public/' . $materi->file_path), $materi->file_name);
    }

    /**
     * Show download statistics for a specific material.
     */
    public function showDownloads(Materi $materi)
    {
        // Pastikan siswa bisa mengakses materi untuk kelasnya
        if ($materi->kelas_id !== Auth::user()->siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $downloads = MateriDownload::with('siswa')
            ->where('materi_id', $materi->id)
            ->orderBy('downloaded_at', 'desc')
            ->get();

        return view('siswa.materi.downloads', compact('materi', 'downloads'));
    }
}
