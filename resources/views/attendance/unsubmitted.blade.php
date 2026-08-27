<x-app-layout>
    <x-slot name="title">
        Daftar User Belum Absen
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar User Belum Absen Hari Ini') }}
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
                                <th class="px-4 py-3">EMAIL</th>
                                <th class="px-4 py-3 text-center">STATUS ABSEN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usersNotAttended as $index => $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center font-medium text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-bold text-red-800 bg-red-100 rounded-full">
                                            BELUM ABSEN
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                        Semua user sudah melakukan presensi hari ini 🎉
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>