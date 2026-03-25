<?php

use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Guru\PresensiController;
use App\Http\Controllers\Guru\AssignmentController;
use App\Http\Controllers\Siswa\MateriController;
use App\Http\Controllers\Siswa\AbsenMandiriController;
use App\Http\Controllers\Siswa\DataGuruController;
use App\Http\Controllers\Siswa\TugasController as SiswaTugasController;
use App\Http\Controllers\Siswa\RiwayatPresensiController;
use App\Http\Controllers\Admin\SchoolLocationController;
use App\Http\Controllers\Api\GpsValidationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard'); 
    } elseif ($user->role === 'guru') {
        return redirect()->route('guru.dashboard');
    } elseif ($user->role === 'siswa') {
        return redirect()->route('siswa.dashboard');
    }
    return redirect('/home');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/users', function () {
})->middleware(['auth', 'role:admin']);

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [AdminDashboardController::class, 'export'])->name('dashboard.export');
    
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    // Export route harus didefinisikan SEBELUM resource route untuk menghindari konflik
    Route::get('kelas/export-guru', [KelasController::class, 'exportGuruPerKelas'])->name('kelas.export-guru');
    
    Route::resource('kelas', KelasController::class)->parameters([
        'kelas' => 'kelas'
    ]);

    Route::get('kelas/{kelas}/kelola-guru', [KelasController::class, 'kelolaGuru'])->name('kelas.kelola-guru');
    Route::post('kelas/{kelas}/kelola-guru', [KelasController::class, 'syncGuru'])->name('kelas.sync-guru');
    
    // Route untuk mengelola semua guru di semua kelas
    Route::get('kelas/kelola-semua-guru', [KelasController::class, 'kelolaSemuaGuru'])->name('kelas.kelola-semua-guru');
    
    // Admin Notification routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'adminIndex'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // School Location routes
    Route::resource('school-location', SchoolLocationController::class);
    Route::post('school-location/{schoolLocation}/toggle-status', [SchoolLocationController::class, 'toggleStatus'])->name('school-location.toggle-status');
    Route::post('school-location/{schoolLocation}/set-main', [SchoolLocationController::class, 'setMain'])->name('school-location.set-main');
});

Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    
    // Presensi routes
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/{kelas}/input', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/presensi/{kelas}/rekap', [PresensiController::class, 'rekap'])->name('presensi.rekap');
    Route::get('/presensi/{kelas}/export', [PresensiController::class, 'exportRekap'])->name('presensi.export');
    
    // Presensi Session routes
    Route::get('/presensi/{kelas}/open-session', [PresensiController::class, 'openSession'])->name('presensi.open-session');
    Route::get('/presensi/{kelas}/close-session', [PresensiController::class, 'closeSession'])->name('presensi.close-session');
    Route::get('/presensi/{kelas}/session-status', [PresensiController::class, 'getSessionStatus'])->name('presensi.session-status');
    Route::get('/presensi/{kelas}/check-open', [PresensiController::class, 'checkPresensiOpen'])->name('presensi.check-open');
    
    // Siswa data routes
    Route::get('/siswa', [PresensiController::class, 'siswa'])->name('siswa.index');
    Route::get('/siswa/{kelas}', [PresensiController::class, 'siswa'])->name('siswa.show');
    
    // Absen Mandiri routes
    Route::get('/absen-mandiri/{kelas}', [\App\Http\Controllers\Guru\AbsenMandiriController::class, 'index'])->name('absen-mandiri.index');
    Route::get('/absen-mandiri/{kelas}/{absen}', [\App\Http\Controllers\Guru\AbsenMandiriController::class, 'show'])->name('absen-mandiri.show');
    Route::post('/absen-mandiri/{kelas}/{absen}/update-status', [\App\Http\Controllers\Guru\AbsenMandiriController::class, 'updateStatus'])->name('absen-mandiri.update-status');
    
    // Assignment routes
    Route::resource('assignment', AssignmentController::class);
    Route::post('/assignment/{submission}/grade', [AssignmentController::class, 'grade'])->name('assignment.grade');
    Route::get('/assignment/{assignment}/download', [AssignmentController::class, 'downloadFile'])->name('assignment.download');
    Route::get('/assignment/submission/{submission}/download', [AssignmentController::class, 'downloadSubmission'])->name('assignment.download-submission'); 
    
    // Materi routes
    Route::resource('materi', MateriController::class);
    
    // Notification routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    
    // Riwayat Presensi
    Route::get('/riwayat-presensi', [RiwayatPresensiController::class, 'index'])->name('riwayat-presensi.index');
    
    // Data Guru
    Route::get('/data-guru', [DataGuruController::class, 'index'])->name('data-guru.index');
    Route::get('/data-guru/{guru}', [DataGuruController::class, 'show'])->name('data-guru.show');
    
    // Absen Mandiri
    Route::get('/absen-mandiri', [AbsenMandiriController::class, 'index'])->name('absen-mandiri.index');
    Route::get('/absen-mandiri/create', [AbsenMandiriController::class, 'create'])->name('absen-mandiri.create');
    Route::post('/absen-mandiri', [AbsenMandiriController::class, 'store'])->name('absen-mandiri.store');
    Route::get('/absen-mandiri/{absen}', [AbsenMandiriController::class, 'show'])->name('absen-mandiri.show');
    Route::delete('/absen-mandiri/{absen}', [AbsenMandiriController::class, 'destroy'])->name('absen-mandiri.destroy');
    Route::get('/absen-mandiri/check-status', [AbsenMandiriController::class, 'checkPresensiStatus'])->name('absen-mandiri.check-status');
    Route::post('/absen-mandiri/validate-gps', [AbsenMandiriController::class, 'validateGps'])->name('absen-mandiri.validate-gps');

    // Tugas
    Route::get('/tugas', [SiswaTugasController::class, 'index'])->name('tugas.index');
    Route::get('/tugas/{tugas}', [SiswaTugasController::class, 'show'])->name('tugas.show');
    Route::post('/tugas/{tugas}/submit', [SiswaTugasController::class, 'submit'])->name('tugas.submit');

    //Materi
    Route::resource('materi', MateriController::class);
    Route::get('/materi/{materi}/download', [MateriController::class, 'download'])->name('materi.download');
    Route::get('/materi/{materi}/downloads', [MateriController::class, 'showDownloads'])->name('materi.downloads');
    
    // Siswa Notification routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'siswaIndex'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::get('/shared/data-kelas', function () {
})->middleware(['auth', 'role:admin,guru']);

// Route untuk error unauthorized
Route::get('/error/unauthorized', function () {
    return view('errors.unauthorized');
})->name('error.unauthorized');

// API Routes for GPS Validation
Route::prefix('api')->group(function () {
    Route::post('/gps/validate', [GpsValidationController::class, 'validateLocation'])->name('api.gps.validate');
    Route::get('/gps/school-location', [GpsValidationController::class, 'getSchoolLocation'])->name('api.gps.school-location');
    Route::post('/gps/test', [GpsValidationController::class, 'testLocation'])->name('api.gps.test');
});

require __DIR__.'/auth.php';
