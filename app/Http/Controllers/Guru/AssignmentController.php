<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Kelas;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments created by the teacher
     */
    public function index()
    {
        $tugas = Tugas::where('guru_id', Auth::user()->id)
            ->with(['kelas', 'pengumpulanTugas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('guru.assignment.index', compact('tugas'));
    }

    /**
     * Show the form for creating a new assignment
     */
    public function create()
    {
        // Get classes that the teacher teaches with student count
        $kelasList = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->withCount('siswaData')->get();

        return view('guru.assignment.create', compact('kelasList'));
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'deadline' => 'required|date|after:today',
            'waktu_deadline' => 'nullable|date_format:H:i',
            'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Verify teacher teaches this class
        $kelas = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->findOrFail($request->kelas_id);

        $data = $request->only(['judul', 'deskripsi', 'kelas_id', 'deadline', 'waktu_deadline']);
        $data['guru_id'] = Auth::user()->id;

        // Handle file upload
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('assignments', $filename, 'public');
            
            $data['file_attachment'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
        }

        Tugas::create($data);

        return redirect()->route('guru.assignment.index')
            ->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Display the specified assignment
     */
    public function show(Tugas $assignment)
    {
        // Verify teacher owns this assignment
        if ($assignment->guru_id !== Auth::user()->id) {
            abort(403);
        }

        $assignment->load(['kelas', 'pengumpulanTugas.siswa']);
        
        // Get submission statistics
        $totalSiswa = $assignment->kelas->siswa()->count();
        $totalSubmissions = $assignment->pengumpulanTugas()->count();
        $onTimeSubmissions = $assignment->pengumpulanTugas()->where('status', 'tepat_waktu')->count();
        $lateSubmissions = $assignment->pengumpulanTugas()->where('status', 'terlambat')->count();

        return view('guru.assignment.show', compact('assignment', 'totalSiswa', 'totalSubmissions', 'onTimeSubmissions', 'lateSubmissions'));
    }

    /**
     * Show the form for editing the specified assignment
     */
    public function edit(Tugas $assignment)
    {
        // Verify teacher owns this assignment
        if ($assignment->guru_id !== Auth::user()->id) {
            abort(403);
        }

        // Only allow editing if no submissions yet
        if ($assignment->pengumpulanTugas()->count() > 0) {
            return redirect()->route('guru.assignment.show', $assignment)
                ->with('error', 'Tugas tidak dapat diedit karena sudah ada pengumpulan dari siswa.');
        }

        $kelasList = Kelas::whereHas('guruPengajar', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->get();

        return view('guru.assignment.edit', compact('assignment', 'kelasList'));
    }

    /**
     * Update the specified assignment
     */
    public function update(Request $request, Tugas $assignment)
    {
        // Verify teacher owns this assignment
        if ($assignment->guru_id !== Auth::user()->id) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'deadline' => 'required|date|after:today',
            'waktu_deadline' => 'nullable|date_format:H:i',
            'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'kelas_id', 'deadline', 'waktu_deadline']);

        // Handle file upload
        if ($request->hasFile('file_attachment')) {
            // Delete old file if exists
            if ($assignment->file_attachment) {
                Storage::disk('public')->delete($assignment->file_attachment);
            }

            $file = $request->file('file_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('assignments', $filename, 'public');
            
            $data['file_attachment'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
        }

        $assignment->update($data);

        return redirect()->route('guru.assignment.show', $assignment)
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified assignment
     */
    public function destroy(Tugas $assignment)
    {
        // Verify teacher owns this assignment
        if ($assignment->guru_id !== Auth::user()->id) {
            abort(403);
        }

        // Delete file if exists
        if ($assignment->file_attachment) {
            Storage::disk('public')->delete($assignment->file_attachment);
        }

        $assignment->delete();

        return redirect()->route('guru.assignment.index')
            ->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Grade a submission
     */
    public function grade(Request $request, PengumpulanTugas $submission)
    {
        // Verify teacher owns the assignment
        if ($submission->tugas->guru_id !== Auth::user()->id) {
            abort(403);
        }

        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'nilai' => $request->nilai,
            'feedback' => $request->feedback,
        ]);

        return redirect()->back()
            ->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Download assignment file
     */
    public function downloadFile(Tugas $assignment)
    {
        // Verify teacher owns this assignment
        if ($assignment->guru_id !== Auth::user()->id) {
            abort(403);
        }

        if (!$assignment->file_attachment) {
            abort(404);
        }

        return Storage::disk('public')->download($assignment->file_attachment, $assignment->file_name);
    }

    /**
     * Download student submission file
     */
    public function downloadSubmission(PengumpulanTugas $submission)
    {
        // Verify teacher owns the assignment
        if ($submission->tugas->guru_id !== Auth::user()->id) {
            abort(403);
        }

        if (!$submission->file_submission) {
            abort(404);
        }

        return Storage::disk('public')->download($submission->file_submission, $submission->file_name);
    }
}