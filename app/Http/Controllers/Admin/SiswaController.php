<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa')->with(['siswa.kelas']);
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('nis', 'like', "%{$searchTerm}%")
                  ->orWhereHas('siswa', function($subQ) use ($searchTerm) {
                      $subQ->where('nis', 'like', "%{$searchTerm}%")
                           ->orWhere('alamat', 'like', "%{$searchTerm}%")
                           ->orWhere('nama_orang_tua', 'like', "%{$searchTerm}%")
                           ->orWhere('kontak_orang_tua', 'like', "%{$searchTerm}%")
                           ->orWhereHas('kelas', function($kelasQ) use ($searchTerm) {
                               $kelasQ->where('nama_kelas', 'like', "%{$searchTerm}%")
                                      ->orWhere('tingkat', 'like', "%{$searchTerm}%");
                           });
                  });
            });
        }
        
        $siswas = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelasList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nis' => 'required|string|unique:siswas',
            'kelas_id' => 'nullable|exists:kelas,id',
            'alamat' => 'required|string',
            'nama_orang_tua' => 'required|string',
            'kontak_orang_tua' => 'required|string',
        ]);

        $user = null;
        DB::transaction(function () use ($request, &$user) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            // Buat data relasi di tabel siswas
            $user->siswa()->create([
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
                'alamat' => $request->alamat,
                'nama_orang_tua' => $request->nama_orang_tua,
                'kontak_orang_tua' => $request->kontak_orang_tua,
            ]);
        });
        
        // Create notification for all teachers about new student
        if ($user && $request->kelas_id) {
            $kelas = Kelas::find($request->kelas_id);
            NotificationService::createForAllTeachers(
                'siswa_baru',
                'Siswa Baru Ditambahkan',
                "Siswa baru '{$user->name}' telah ditambahkan ke kelas '{$kelas->nama_kelas}' oleh admin.",
                ['siswa_id' => $user->id, 'siswa_name' => $user->name, 'kelas_id' => $request->kelas_id, 'kelas_name' => $kelas->nama_kelas]
            );
        }
        
        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::where('role', 'siswa')->with(['siswa.kelas'])->findOrFail($id);
        
        if (!$user->siswa) {
            abort(404, 'Data siswa tidak ditemukan');
        }
        
        return view('admin.siswa.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with(['siswa.kelas'])->where('role', 'siswa')->findOrFail($id);
        
        if (!$user->siswa) {
            abort(404, 'Data siswa tidak ditemukan');
        }
        
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('user', 'kelasList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::with('siswa')->where('role', 'siswa')->findOrFail($id);
        
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nis' => ['required', 'string', Rule::unique('siswas')->ignore($user->siswa->id ?? null)],
            'kelas_id' => 'nullable|exists:kelas,id',
            'alamat' => 'required|string',
            'nama_orang_tua' => 'required|string',
            'kontak_orang_tua' => 'required|string',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $user) {
            // Update data di tabel users
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Jika ada password baru, update passwordnya
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Update atau buat data relasi di tabel siswas
            $user->siswa()->updateOrCreate(
                ['user_id' => $user->id], // Kondisi pencarian
                [ // Data yang diupdate/dibuat
                    'nis' => $request->nis,
                    'kelas_id' => $request->kelas_id,
                    'alamat' => $request->alamat,
                    'nama_orang_tua' => $request->nama_orang_tua,
                    'kontak_orang_tua' => $request->kontak_orang_tua,
                ]
            );
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = User::with('siswa')->where('role', 'siswa')->findOrFail($id);

        if ($user->siswa) {
            $user->siswa->delete();
        }


        $user->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }
}
