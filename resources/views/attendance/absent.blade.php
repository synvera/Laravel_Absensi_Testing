<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Ketidakhadiran (Sakit / Izin / Alfa)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @if($todayAttendance)
                    <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-center">
                        Kamu sudah mengisi presensi hari ini dengan status: <strong>{{ strtoupper($todayAttendance->status) }}</strong>
                    </div>
                @else
                    <form action="{{ route('attendance.absent.store') }}" method="POST">
                        @csrf

                        <!-- Status Ketidakhadiran -->
                        <div class="mb-4">
                            <label for="status" class="block font-medium text-sm text-gray-700">Keterangan Tidak Masuk</label>
                            <select name="status" id="status" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Alasan --</option>
                                <option value="sakit">Sakit</option>
                                <option value="izin">Izin</option>
                                <option value="alfa">Alfa / Tanpa Keterangan</option>
                            </select>
                        </div>

                        <!-- Catatan / Alasan -->
                        <div class="mb-4">
                            <label for="note" class="block font-medium text-sm text-gray-700">Alasan / Detail Catatan</label>
                            <textarea name="note" id="note" rows="3" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Demam tinggi / Ada urusan keluarga" required></textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                            Kirim Ketidakhadiran
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>