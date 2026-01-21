<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'guru')->with('guru');
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('nip', 'like', "%{$searchTerm}%")
                  ->orWhereHas('guru', function($subQ) use ($searchTerm) {
                      $subQ->where('nip', 'like', "%{$searchTerm}%")
                           ->orWhere('jabatan', 'like', "%{$searchTerm}%")
                           ->orWhere('alamat', 'like', "%{$searchTerm}%")
                           ->orWhere('no_telepon', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        $gurus = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.guru.index', compact('gurus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'required|string|unique:gurus',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string',
            'jabatan' => 'required|string',
        ]);

        // Buat user terlebih dahulu
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
        ]);

        // Buat data guru
        Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::where('role', 'guru')->with('guru')->findOrFail($id);
        
        if (!$user->guru) {
            abort(404, 'Data guru tidak ditemukan');
        }
        
        $guru = $user->guru;
        
        return view('admin.guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        // Prepare validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($guru->user_id)],
            'nip' => ['required', 'string', Rule::unique('gurus')->ignore($guru->id)],
            'alamat' => 'required|string',
            'no_telepon' => 'required|string',
            'jabatan' => 'required|string',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Update user
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $guru->user->update($userData);

        // Update guru
        $guru->update([
            'nip' => $request->nip,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        $guru->delete();
        
        $guru->user->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus');
    }
}
