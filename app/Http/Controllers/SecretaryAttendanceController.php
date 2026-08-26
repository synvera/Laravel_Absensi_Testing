<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;

class SecretaryAttendanceController extends Controller
{
    public function index()
    {
        // 1. Data Tabel Rekapitulasi Total Kehadiran Per Siswa ($summary)
        $summary = User::where('role', 'siswa')
            ->withCount([
                'attendances as total_hadir' => function ($query) {
                    $query->where('status', 'hadir');
                },
                'attendances as total_sakit' => function ($query) {
                    $query->where('status', 'sakit');
                },
                'attendances as total_izin' => function ($query) {
                    $query->where('status', 'izin');
                },
                'attendances as total_alfa' => function ($query) {
                    $query->where('status', 'alfa');
                },
            ])
            ->get();

        // 2. Data Tabel Kelola Detail Riwayat Presensi dengan Pagination ($attendances)
        $attendances = Attendance::with('user')->latest()->paginate(10);

        return view('secretary.index', compact('summary', 'attendances'));
    }

    // Hapus 1 Record Presensi (Tabel Detail)
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $user = User::find($attendance->user_id);

        if ($user) {
            // Hapus semua presensi milik user ini
            $user->attendances()->delete();
            // Hapus user agar hilang dari tabel Rekapitulasi
            $user->delete();
        } else {
            $attendance->delete();
        }

        return redirect()->back()->with('success', 'Siswa dan seluruh data presensinya berhasil dihapus.');
    }

    // Hapus Akun Siswa & Seluruh Presensinya (Tabel Rekapitulasi)
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        
        // Hapus presensi milik user terlebih dahulu
        $user->attendances()->delete();
        $user->delete();

        return redirect()->back()->with('success', 'Siswa beserta seluruh riwayat presensinya berhasil dihapus.');
    }
}