<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Carbon\Carbon;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Auth::user();
        
        // Get kelas_id from Siswa model relationship
        $kelasId = $siswa->siswa ? $siswa->siswa->kelas_id : null;
        
        if (!$kelasId) {
            $tugasList = collect(); // Empty collection if no kelas
        } else {
            $tugasList = Tugas::where('kelas_id', $kelasId)
                ->with(['pengumpulanTugas' => fn($q) => $q->where('siswa_id', $siswa->id)])
                ->latest()
                ->paginate(10);
        }

        return view('siswa.tugas.index', compact('tugasList'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tugas $tugas)
    {
        $siswa = Auth::user();
        $kelasId = $siswa->siswa ? $siswa->siswa->kelas_id : null;
        
        // Check if tugas belongs to student's class
        if (!$kelasId || $tugas->kelas_id != $kelasId) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }
        
        $pengumpulan = $tugas->pengumpulanTugas()->where('siswa_id', $siswa->id)->first();
        return view('siswa.tugas.show', compact('tugas', 'pengumpulan'));
    }

    /**
     * Submit assignment
     */
    public function submit(Request $request, Tugas $tugas)
    {
        $siswa = Auth::user();
        $kelasId = $siswa->siswa ? $siswa->siswa->kelas_id : null;
        
        // Check if tugas belongs to student's class
        if (!$kelasId || $tugas->kelas_id != $kelasId) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }
        
        $request->validate([
            'jawaban' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if ($value) {
                    $wordCount = str_word_count($value);
                    if ($wordCount > 200) {
                        $fail('Jawaban tidak boleh lebih dari 200 kata.');
                    }
                }
            }],
            'file_submission' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png,zip', 'max:5120'], // maks 5MB
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file_submission')) {
            $file = $request->file('file_submission');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('submissions/' . Auth::id(), 'public');
        }

        $deadline = Carbon::parse($tugas->deadline);
        $status = now()->gt($deadline) ? 'terlambat' : 'tepat_waktu';

        // updateOrCreate agar siswa bisa memperbaiki tugasnya jika diperlukan
        PengumpulanTugas::updateOrCreate(
            [
                'tugas_id' => $tugas->id,
                'siswa_id' => Auth::id(),
            ],
            [
                'jawaban' => $request->jawaban,
                'file_submission' => $filePath,
                'file_name' => $fileName,
                'submitted_at' => now(),
                'status' => $status,
            ]
        );

        return redirect()->route('siswa.tugas.show', $tugas)->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
