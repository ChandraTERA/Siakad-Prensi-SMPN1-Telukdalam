@extends('layouts.admin')

@section('title', 'Manajemen Guru')

@section('content')
    <div class="flex flex-col gap-8">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold">Manajemen Data Guru</h1>
            <a href="#" class="btn btn-primary">
                <x-lucide-plus class="w-5 h-5" />
                Tambah Guru
            </a>
        </div>

        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra">
                <thead class="text-base">
                    <tr>
                        <th>No.</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gurus as $index => $user)
                        <tr>
                            <th>{{ $gurus->firstItem() + $index }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->nip ?? '-' }}</td>
                            <td>{{ $user->guru->jabatan ?? '-' }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="#" class="btn btn-sm btn-info btn-outline">
                                        <x-lucide-edit class="w-4 h-4" />
                                    </a>
                                    <button class="btn btn-sm btn-error btn-outline">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Tidak ada data guru yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $gurus->links() }}
        </div>
    </div>
@endsection
