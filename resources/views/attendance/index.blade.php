<x-app-layout>
    <x-slot name="title">
        Riwayat Presensi Saya
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Presensi Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-center">NO</th>
                                <th class="px-4 py-3">NAMA LENGKAP</th>
                                <th class="px-4 py-3">TANGGAL</th>
                                <th class="px-4 py-3">WAKTU</th>
                                <th class="px-4 py-3 text-center">STATUS</th>
                                <th class="px-4 py-3">KETERANGAN</th>
                                <th class="px-4 py-3">KOORDINAT (LAT, LONG)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $index => $attendance)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center font-medium text-gray-900">
                                        {{ $attendances->firstItem() + $index }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800">
                                        {{ $attendance->user->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $attendance->created_at->format('d F Y') }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">
                                        {{ $attendance->created_at->format('H:i:s') }} WIB
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($attendance->status == 'hadir')
                                            <span class="px-2 py-1 text-xs font-bold text-green-800 bg-green-100 rounded-full">HADIR</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-bold text-red-800 bg-red-100 rounded-full">TIDAK HADIR</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $attendance->note ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        @if($attendance->latitude && $attendance->longitude)
                                            {{ $attendance->latitude }}, {{ $attendance->longitude }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada riwayat presensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Link Paginate -->
                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>