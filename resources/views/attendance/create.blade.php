<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Absen Masuk (Hadir)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Flash Message Success --}}
                @if(session('success'))
                    <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: center;">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Flash Message Error --}}
                @if(session('error'))
                    <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: center;">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Kondisi jika user sudah absen hari ini --}}
                @if(isset($todayAttendance) && $todayAttendance)
                    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                        <p class="font-bold text-lg">Kamu sudah melakukan presensi hari ini!</p>
                        <p class="text-sm mt-1">Status: <strong class="uppercase">{{ $todayAttendance->status }}</strong></p>
                        <p class="text-xs mt-1">Waktu: {{ $todayAttendance->created_at->format('H:i') }} WIB</p>
                        @if($todayAttendance->latitude && $todayAttendance->longitude)
                            <p class="text-xs mt-1 text-gray-500">Koordinat: {{ $todayAttendance->latitude }}, {{ $todayAttendance->longitude }}</p>
                        @endif
                    </div>
                @else
                    {{-- Form Absen Masuk --}}
                    <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
                        @csrf

                        <input type="hidden" name="status" value="hadir">
                        {{-- Input Hidden untuk Koordinat GPS --}}
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="mb-6 text-center">
                            <p class="text-gray-600 mb-2">Klik tombol di bawah untuk melakukan presensi masuk hari ini:</p>
                            <p class="text-3xl font-extrabold text-gray-900" id="clock">
                                {{ now()->format('H:i') }} WIB
                            </p>
                            <p id="location-status" class="text-xs text-gray-500 mt-2">Mendeteksi lokasi GPS...</p>
                        </div>

                        {{-- Tombol Utama dengan Fallback Style --}}
                        <button type="submit" 
                                id="submitBtn"
                                disabled
                                class="w-full px-4 py-3 bg-gray-400 text-white font-bold rounded-lg transition"
                                style="background-color: #9ca3af; color: white; width: 100%; padding: 12px; border-radius: 8px; font-weight: bold; cursor: not-allowed; border: none;">
                            Menyiapkan Lokasi...
                        </button>
                    </form>
                @endif

                {{-- Link Ke Form Tidak Masuk --}}
                <div class="mt-6 text-center border-t pt-4" style="margin-top: 1.5rem; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                    <a href="{{ route('attendance.absent.create') }}" class="text-sm text-red-600 hover:underline" style="color: #dc2626; text-decoration: underline;">
                        Tidak bisa hadir? Isi Form Sakit / Izin / Alfa di sini
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Jam Realtime
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const el = document.getElementById('clock');
            if (el) el.textContent = `${hours}:${minutes} WIB`;
        }
        setInterval(updateClock, 1000);

        // Ambil Koordinat GPS otomatis saat halaman dibuka
        document.addEventListener('DOMContentLoaded', function () {
            const submitBtn = document.getElementById('submitBtn');
            const locStatus = document.getElementById('location-status');

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        // Jika berhasil mendapatkan lokasi
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                        
                        // Aktifkan tombol submit
                        submitBtn.disabled = false;
                        submitBtn.style.backgroundColor = '#4f46e5';
                        submitBtn.style.cursor = 'pointer';
                        submitBtn.textContent = 'Klik Untuk Absen Masuk';
                        locStatus.textContent = 'Lokasi berhasil terdeteksi ✓';
                        locStatus.classList.remove('text-gray-500');
                        locStatus.classList.add('text-green-600');
                    },
                    function (error) {
                        // Jika gagal / izin lokasi ditolak
                        locStatus.textContent = 'Gagal mendeteksi GPS. Aktifkan izin lokasi browser!';
                        locStatus.classList.remove('text-gray-500');
                        locStatus.classList.add('text-red-600');
                        submitBtn.textContent = 'Lokasi Tidak Ditemukan';
                    },
                    { enableHighAccuracy: true }
                );
            } else {
                locStatus.textContent = 'Browser Anda tidak Mendukung Geolocation.';
                locStatus.classList.add('text-red-600');
            }
        });
    </script>
</x-app-layout>