# Fitur Search Dinamis - Dokumentasi Implementasi

## Overview

Fitur search dinamis telah berhasil diimplementasikan untuk ketiga halaman admin utama: Guru, Siswa, dan Kelas. Fitur ini memungkinkan admin untuk mencari data dengan mudah menggunakan berbagai kriteria pencarian.

## Fitur yang Diimplementasikan

### 1. Backend Implementation

#### GuruController

-   **Search Fields**: Nama, Email, NIP, Jabatan, Alamat, No Telepon
-   **Search Logic**: Mencari di tabel `users` dan relasi `guru`
-   **Query**: Menggunakan `whereHas` untuk pencarian di relasi

#### SiswaController

-   **Search Fields**: Nama, Email, NIS, Alamat, Nama Orang Tua, Kontak Orang Tua, Nama Kelas, Tingkat Kelas
-   **Search Logic**: Mencari di tabel `users`, relasi `siswa`, dan relasi `kelas`
-   **Query**: Multi-level `whereHas` untuk pencarian nested relationships

#### KelasController

-   **Search Fields**: Nama Kelas, Tingkat, Nama Wali Kelas, Email Wali Kelas, Nama Guru Pengajar, Email Guru Pengajar
-   **Search Logic**: Mencari di tabel `kelas` dan relasi `waliKelas`, `guruPengajar`
-   **Query**: Multiple `whereHas` untuk pencarian di berbagai relasi

### 2. Frontend Implementation

#### Search Form

-   **Form Method**: GET request untuk URL-friendly search
-   **Input Field**: Text input dengan placeholder yang sesuai
-   **Search Icon**: SVG icon untuk visual clarity
-   **Clear Button**: X button untuk menghapus search (muncul saat ada search term)

#### Search Results Indicator

-   **Info Bar**: Menampilkan jumlah hasil pencarian
-   **Search Term**: Menampilkan kata kunci yang dicari
-   **Clear Filter**: Link untuk menghapus filter pencarian
-   **Styling**: Blue background untuk membedakan dari konten normal

### 3. User Experience Features

#### Real-time Search

-   **Form Submission**: Search dilakukan saat form disubmit
-   **URL Preservation**: Search term tersimpan di URL untuk bookmarking
-   **Pagination**: Search results tetap menggunakan pagination dengan `withQueryString()`

#### Visual Feedback

-   **Search Results Count**: Menampilkan "X dari Y hasil"
-   **Active Search State**: Visual indicator saat sedang dalam mode search
-   **Clear Search**: Easy way untuk kembali ke view normal

## Cara Penggunaan

### Untuk Admin:

1. **Akses halaman admin** (Guru/Siswa/Kelas)
2. **Gunakan search box** di bagian atas tabel
3. **Ketik kata kunci** yang ingin dicari
4. **Tekan Enter** atau klik di luar input untuk melakukan pencarian
5. **Lihat hasil** dengan indikator jumlah hasil
6. **Hapus search** dengan tombol X atau link "Hapus filter"

### Contoh Pencarian:

-   **Guru**: "Matematika", "SMA", "guru@email.com", "12345"
-   **Siswa**: "Ahmad", "XII", "08123456789", "SMA Negeri 1"
-   **Kelas**: "XII IPA", "SMA", "Budi Santoso", "wali@email.com"

## Technical Details

### Database Queries

-   **LIKE Operator**: Menggunakan `%search%` untuk partial matching
-   **Case Insensitive**: Pencarian tidak case-sensitive
-   **Multiple Fields**: Pencarian dilakukan di beberapa field sekaligus
-   **Relationship Search**: Menggunakan `whereHas` untuk pencarian di relasi

### Performance Considerations

-   **Indexed Fields**: Field yang sering dicari sudah di-index
-   **Eager Loading**: Menggunakan `with()` untuk menghindari N+1 queries
-   **Pagination**: Hasil pencarian tetap menggunakan pagination

### URL Structure

-   **Clean URLs**: `/admin/guru?search=matematika`
-   **Bookmarkable**: URL dapat di-bookmark dengan search term
-   **Browser History**: Search term tersimpan di browser history

## Status Implementasi

✅ **Completed**: Semua fitur search telah diimplementasikan dan berfungsi

-   Backend search logic untuk ketiga controller
-   Frontend search form dengan UX yang baik
-   Search results indicator dan clear functionality
-   URL-friendly search dengan pagination
-   Responsive design untuk mobile dan desktop

## Testing

-   ✅ Search functionality tested dengan tinker
-   ✅ Routes verified dan berfungsi
-   ✅ Form submission dan URL handling
-   ✅ Pagination dengan search term preservation
