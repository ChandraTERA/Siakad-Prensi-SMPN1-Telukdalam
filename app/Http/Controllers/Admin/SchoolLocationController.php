<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = SchoolLocation::latest()->paginate(10);
        return view('admin.school-location.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.school-location.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:10|max:1000',
            'alamat' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        SchoolLocation::create($validated);

        return redirect()->route('admin.school-location.index')
            ->with('success', 'Lokasi sekolah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolLocation $schoolLocation)
    {
        return view('admin.school-location.show', compact('schoolLocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolLocation $schoolLocation)
    {
        return view('admin.school-location.edit', compact('schoolLocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolLocation $schoolLocation)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:10|max:1000',
            'alamat' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $schoolLocation->update($validated);

        return redirect()->route('admin.school-location.index')
            ->with('success', 'Lokasi sekolah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolLocation $schoolLocation)
    {
        $schoolLocation->delete();

        return redirect()->route('admin.school-location.index')
            ->with('success', 'Lokasi sekolah berhasil dihapus');
    }

    /**
     * Toggle status aktif lokasi
     */
    public function toggleStatus(SchoolLocation $schoolLocation)
    {
        $schoolLocation->update(['is_active' => !$schoolLocation->is_active]);

        $status = $schoolLocation->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.school-location.index')
            ->with('success', "Lokasi sekolah berhasil {$status}");
    }

    /**
     * Set sebagai lokasi utama
     */
    public function setMain(SchoolLocation $schoolLocation)
    {
        // Nonaktifkan semua lokasi lainnya
        SchoolLocation::where('id', '!=', $schoolLocation->id)->update(['is_active' => false]);
        
        // Aktifkan lokasi yang dipilih
        $schoolLocation->update(['is_active' => true]);

        return redirect()->route('admin.school-location.index')
            ->with('success', 'Lokasi utama berhasil diubah');
    }
}
