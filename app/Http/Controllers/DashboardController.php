<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Siswa
        $totalSiswa = User::where('role', 'siswa')->count();

        // Presensi Hari Ini
        $todayAttendance = Attendance::whereDate('created_at', today())->get();

        $hadirHariIni = $todayAttendance->where('status', 'hadir')->count();
        $sakitHariIni = $todayAttendance->where('status', 'sakit')->count();
        $izinHariIni  = $todayAttendance->where('status', 'izin')->count();
        $alfaHariIni  = $todayAttendance->where('status', 'alfa')->count();

        // Akumulasi Total Presensi Keseluruhan
        $totalHadir = Attendance::where('status', 'hadir')->count();
        $totalSakit = Attendance::where('status', 'sakit')->count();
        $totalIzin  = Attendance::where('status', 'izin')->count();
        $totalAlfa  = Attendance::where('status', 'alfa')->count();

        return view('dashboard', compact(
            'totalSiswa',
            'hadirHariIni', 'sakitHariIni', 'izinHariIni', 'alfaHariIni',
            'totalHadir', 'totalSakit', 'totalIzin', 'totalAlfa'
        ));
    }
}