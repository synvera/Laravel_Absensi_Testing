<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // --- RIWAYAT PRESENSI (Bisa Dilihat oleh Semua Siswa) ---
    public function index()
    {
        // 1. Rekapitulasi total kehadiran per siswa agar transparan
        $summary = User::where('role', 'siswa')
            ->withCount([
                'attendances as total_hadir' => function ($q) { $q->where('status', 'hadir'); },
                'attendances as total_sakit' => function ($q) { $q->where('status', 'sakit'); },
                'attendances as total_izin'  => function ($q) { $q->where('status', 'izin'); },
                'attendances as total_alfa'  => function ($q) { $q->where('status', 'alfa'); },
            ])->get();

        // 2. Seluruh riwayat presensi siswa
        $attendances = Attendance::with('user')
                        ->latest()
                        ->paginate(10);

        return view('attendance.index', compact('summary', 'attendances'));
    }

    // --- ABSEN MASUK (CLOCK IN) ---
    public function create()
    {
        // Sekretaris tidak boleh absen
        if (Auth::user()->role === 'sekretaris') {
            return redirect()->route('secretary.index')
                             ->with('error', 'Akses ditolak. Sekretaris tidak diperbolehkan melakukan absen!');
        }

        $todayAttendance = Attendance::where('user_id', Auth::id())
                            ->whereDate('created_at', today())
                            ->first();

        return view('attendance.create', compact('todayAttendance'));
    }

    public function store(Request $request)
    {
        // Sekretaris tidak boleh absen
        if (Auth::user()->role === 'sekretaris') {
            return redirect()->route('secretary.index')
                             ->with('error', 'Akses ditolak. Sekretaris tidak diperbolehkan melakukan absen!');
        }

        $userId = Auth::id();

        $sudahAbsen = Attendance::where('user_id', $userId)
                        ->whereDate('created_at', today())
                        ->exists();

        if ($sudahAbsen) {
            return redirect()->route('attendance.create')
                             ->with('error', 'Kamu sudah melakukan presensi hari ini!');
        }

        // Simpan data presensi lengkap dengan koordinat GPS
        Attendance::create([
            'user_id'    => $userId,
            'tanggal'    => date('Y-m-d'),
            'waktu'      => date('H:i:s'),
            'status'     => 'hadir',
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'note'       => 'Hadir Tepat Waktu',
        ]);

        return redirect()->route('attendance.create')
                         ->with('success', 'Berhasil Absen Masuk dan Menyimpan Lokasi!');
    }

    // --- ABSEN TIDAK MASUK (SAKIT / IZIN / ALFA) ---
    public function createAbsent()
    {
        // Sekretaris tidak boleh absen
        if (Auth::user()->role === 'sekretaris') {
            return redirect()->route('secretary.index')
                             ->with('error', 'Akses ditolak. Sekretaris tidak diperbolehkan melakukan absen!');
        }

        $todayAttendance = Attendance::where('user_id', Auth::id())
                            ->whereDate('created_at', today())
                            ->first();

        return view('attendance.absent', compact('todayAttendance'));
    }

    public function storeAbsent(Request $request)
    {
        // Sekretaris tidak boleh absen
        if (Auth::user()->role === 'sekretaris') {
            return redirect()->route('secretary.index')
                             ->with('error', 'Akses ditolak. Sekretaris tidak diperbolehkan melakukan absen!');
        }

        $request->validate([
            'status' => 'required|in:sakit,izin,alfa',
            'note'   => 'required|string|max:255',
        ]);

        $userId = Auth::id();

        $sudahAbsen = Attendance::where('user_id', $userId)
                        ->whereDate('created_at', today())
                        ->exists();

        if ($sudahAbsen) {
            return redirect()->route('attendance.absent.create')
                             ->with('error', 'Kamu sudah mengisi presensi hari ini!');
        }

        Attendance::create([
            'user_id' => $userId,
            'status'  => $request->status,
            'note'    => $request->note,
        ]);

        return redirect()->route('attendance.absent.create')
                         ->with('success', 'Keterangan tidak masuk berhasil disimpan!');
    }
}