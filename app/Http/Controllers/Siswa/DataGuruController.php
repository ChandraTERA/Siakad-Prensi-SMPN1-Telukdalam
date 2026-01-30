<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataGuruController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Auth::user();
        
        // Get search parameter
        $search = $request->get('search');
        
        // Get all teachers with their user data
        $guru = User::where('role', 'guru')
            ->with('guru')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('guru', function ($q) use ($search) {
                        $q->where('mata_pelajaran', 'like', "%{$search}%")
                          ->orWhere('nip', 'like', "%{$search}%");
                    });
            })
            ->orderBy('name')
            ->paginate(12);
        
        return view('siswa.data-guru', compact('siswa', 'guru', 'search'));
    }
    
    public function show($id)
    {
        $siswa = Auth::user();
        $guru = User::where('role', 'guru')
            ->with('guru')
            ->findOrFail($id);
        
        return view('siswa.data-guru-detail', compact('siswa', 'guru'));
    }
}