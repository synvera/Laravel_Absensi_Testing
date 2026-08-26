<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Presensi Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('secretary.update', $attendance->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Siswa</label>
                        <input type="text" value="{{ $attendance->user->name }}" class="w-full mt-1 border-gray-300 rounded-md bg-gray-100" disabled>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status Presensi</label>
                        <select name="status" id="status" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                            <option value="hadir" {{ $attendance->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="sakit" {{ $attendance->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="izin" {{ $attendance->status == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="alfa" {{ $attendance->status == 'alfa' ? 'selected' : '' }}>Alfa</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="note" class="block text-sm font-medium text-gray-700">Keterangan / Alasan</label>
                        <textarea name="note" id="note" rows="3" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>{{ $attendance->note }}</textarea>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('secretary.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 border-none cursor-pointer" style="background-color: green;">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>