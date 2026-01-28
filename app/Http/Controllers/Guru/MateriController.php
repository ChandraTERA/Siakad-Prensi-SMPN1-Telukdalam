<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materis = Materi::with(['mataPelajaran', 'kelas', 'downloads'])
            ->where('guru_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('guru.materi.index', compact('materis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mataPelajarans = MataPelajaran::all();
        $kelas = Kelas::all();
        
        return view('guru.materi.create', compact('mataPelajarans', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_materi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png,gif,mp4,mp3,zip,rar'
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('materi', $fileName, 'public');

        Materi::create([
            'nama_materi' => $request->nama_materi,
            'deskripsi' => $request->deskripsi,
            'mapel_id' => $request->mapel_id,
            'kelas_id' => $request->kelas_id,
            'guru_id' => Auth::id(),
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('guru.materi.index')
            ->with('success', 'Materi berhasil diupload!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Materi $materi)
    {
        // Pastikan guru hanya bisa melihat materi yang mereka buat
        if ($materi->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }
        
        $materi->load(['mataPelajaran', 'kelas', 'downloads.siswa']);
        
        return view('guru.materi.show', compact('materi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materi $materi)
    {
        // Pastikan guru hanya bisa mengedit materi yang mereka buat
        if ($materi->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }
        
        $mataPelajarans = MataPelajaran::all();
        $kelas = Kelas::all();
        
        return view('guru.materi.edit', compact('materi', 'mataPelajarans', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materi $materi)
    {
        // Pastikan guru hanya bisa mengupdate materi yang mereka buat
        if ($materi->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }
        
        $request->validate([
            'nama_materi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png,gif,mp4,mp3,zip,rar'
        ]);

        $data = [
            'nama_materi' => $request->nama_materi,
            'deskripsi' => $request->deskripsi,
            'mapel_id' => $request->mapel_id,
            'kelas_id' => $request->kelas_id,
        ];

        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materi', $fileName, 'public');

            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $materi->update($data);

        return redirect()->route('guru.materi.index')
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materi $materi)
    {
        // Pastikan guru hanya bisa menghapus materi yang mereka buat
        if ($materi->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }
        
        // Hapus file dari storage
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('guru.materi.index')
            ->with('success', 'Materi berhasil dihapus!');
    }
}
