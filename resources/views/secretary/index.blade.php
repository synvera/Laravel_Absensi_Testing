<style>
    @media print {
        .no-print,
        .print\:hidden {
            display: none !important;
        }
    }
</style>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- HEADER PAGE & TOMBOL CETAK -->
            <div class="flex justify-between items-center bg-white p-6 shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Panel Sekretaris - Rekap & Kelola Presensi
                </h2>
                <!-- Tombol Cetak (Disembunyikan saat print) -->
                <button onclick="window.print()" class="no-print px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition duration-150 ease-in-out border-none cursor-pointer" style="background-color: #16a34a; color: #ffffff; padding: 8px 16px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">
                    Cetak Laporan (PDF)
                </button>
            </div>

            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg no-print">
                    {{ session('success') }}
                </div>
            @endif

            <!-- TABEL 1: REKAPITULASI TOTAL KEHADIRAN PER SISWA -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Rekapitulasi Total Kehadiran Per Siswa</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3 text-center">NO</th>
                                <th class="px-4 py-3">NAMA SISWA</th>
                                <th class="px-4 py-3 text-center bg-green-50 text-green-700">HADIR</th>
                                <th class="px-4 py-3 text-center bg-yellow-50 text-yellow-700">SAKIT</th>
                                <th class="px-4 py-3 text-center bg-blue-50 text-blue-700">IZIN</th>
                                <th class="px-4 py-3 text-center bg-red-50 text-red-700">ALFA</th>
                                <th class="px-4 py-3 text-center">TOTAL PRESENSI</th>
                                <th class="px-4 py-3 text-center no-print">AKSI SISWA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($summary as $index => $student)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $student->name }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-green-600">{{ $student->total_hadir }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-yellow-600">{{ $student->total_sakit }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-blue-600">{{ $student->total_izin }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-red-600">{{ $student->total_alfa }}</td>
                                    <td class="px-4 py-3 text-center font-extrabold text-gray-800">
                                        {{ $student->total_hadir + $student->total_sakit + $student->total_izin + $student->total_alfa }}
                                    </td>
                                    <td class="px-4 py-3 text-center no-print">
                                        <form action="{{ route('secretary.users.destroy', $student->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus siswa akan menghapus seluruh data akun dan riwayat absennya. Yakin ingin menghapus {{ $student->name }}?')" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-semibold border-none cursor-pointer">
                                                Hapus Siswa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABEL 2: DETAIL RIWAYAT & KELOLA (Disembunyikan total saat cetak) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 no-print">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Kelola Detail Riwayat Presensi</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 border">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3 text-center">NO</th>
                                <th class="px-4 py-3">NAMA SISWA</th>
                                <th class="px-4 py-3">TANGGAL & WAKTU</th>
                                <th class="px-4 py-3 text-center">STATUS</th>
                                <th class="px-4 py-3">KETERANGAN</th>
                                <th class="px-4 py-3 text-center no-print">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $index => $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center font-medium">{{ $attendances->firstItem() + $index }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $item->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->created_at->format('d M Y, H:i') }} WIB</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full uppercase
                                            {{ strtolower($item->status) == 'hadir' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ strtolower($item->status) == 'sakit' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ strtolower($item->status) == 'izin' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ strtolower($item->status) == 'alfa' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $item->note ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center no-print" style="white-space: nowrap;">
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('secretary.edit', $item->id) }}" style="display: inline-flex; align-items: center; justify-content: center; background-color: #eab308; color: #ffffff; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 12px; text-decoration: none; height: 28px; box-sizing: border-box;">
                                            Edit
                                        </a>
                                        
                                        <!-- Form Hapus -->
                                        <form action="{{ route('secretary.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data presensi ini?')" style="display: inline-block; margin: 0; padding: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="display: inline-flex; align-items: center; justify-content: center; background-color: #dc2626; color: #ffffff; padding: 4px 12px; border-radius: 4px; font-weight: 600; font-size: 12px; border: none; cursor: pointer; height: 28px; box-sizing: border-box;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center">Belum ada riwayat presensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 no-print">
                    {{ $attendances->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>