<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecretaryAttendanceController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PushNotificationController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route Google Auth (Di luar middleware auth agar bisa diakses pengguna yang belum login)
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Route Presensi, Profile, & Dashboard (Wajib Login)
Route::middleware(['auth'])->group(function () {

    // 1. Route Dashboard Utama (Memanggil DashboardController)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Route Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. Route Form Absen & Clock In
    Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // 4. Route Khusus Absen Tidak Masuk (Sakit / Izin / Alfa)
    Route::get('/attendance/absent', [AttendanceController::class, 'createAbsent'])->name('attendance.absent.create');
    Route::post('/attendance/absent', [AttendanceController::class, 'storeAbsent'])->name('attendance.absent.store');

    // 5. Route Clock Out / Absen Pulang
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock_out');

    // 6. Route Riwayat Absensi & Status
    Route::get('/attendance/history', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/status-absen', [AttendanceController::class, 'status'])->name('attendance.status');
    
    // Tambahkan route ini di sini:
    Route::get('/belum-absen', [AttendanceController::class, 'unsubmittedUsers'])->name('attendance.unsubmitted');

    // 7. Route Khusus Sekretaris (Rekap, Edit Presensi, Hapus Presensi, & Hapus User)
    Route::get('/secretary/attendance', [SecretaryAttendanceController::class, 'index'])->name('secretary.index');
    Route::get('/secretary/attendance/{id}/edit', [SecretaryAttendanceController::class, 'edit'])->name('secretary.edit');
    Route::put('/secretary/attendance/{id}', [SecretaryAttendanceController::class, 'update'])->name('secretary.update');
    Route::delete('/secretary/attendance/{id}', [SecretaryAttendanceController::class, 'destroy'])->name('secretary.destroy');
    Route::delete('/secretary/users/{id}', [SecretaryAttendanceController::class, 'destroyUser'])->name('secretary.users.destroy');

    // 8. Endpoint API Status Hari Ini
    Route::get('/api/absensi/status-hari-ini', function () {
        $presensi = \App\Models\Attendance::where('user_id', Auth::id())
                        ->whereDate('created_at', today())
                        ->first();

        if (!$presensi) {
            return response()->json(['message' => 'Belum absen'], 404);
        }

        return response()->json([
            'status' => $presensi->status,
            'timestamp' => $presensi->created_at->format('H:i') . ' WIB',
            'note' => $presensi->note ?? 'Tepat Waktu'
        ]);
    });

    Route::post('/api/save-subscription', [PushNotificationController::class, 'store'])->middleware('auth');
});

require __DIR__.'/auth.php';