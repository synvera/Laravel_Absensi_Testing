<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Data disimpan di atribut HTML agar bersih dari linter JS -->
    <div class="py-12" id="dashboard-data"
         data-hadir="{{ $hadirHariIni }}"
         data-sakit="{{ $sakitHariIni }}"
         data-izin="{{ $izinHariIni }}"
         data-alfa="{{ $alfaHariIni }}"
         data-total-hadir="{{ $totalHadir }}"
         data-total-sakit="{{ $totalSakit }}"
         data-total-izin="{{ $totalIzin }}"
         data-total-alfa="{{ $totalAlfa }}">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card Ringkasan Hari Ini -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 font-semibold uppercase">Hadir Hari Ini</p>
                    <p class="text-3xl font-extrabold text-green-600 mt-2">{{ $hadirHariIni }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500 font-semibold uppercase">Sakit Hari Ini</p>
                    <p class="text-3xl font-extrabold text-yellow-600 mt-2">{{ $sakitHariIni }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 font-semibold uppercase">Izin Hari Ini</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-2">{{ $izinHariIni }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 font-semibold uppercase">Alfa Hari Ini</p>
                    <p class="text-3xl font-extrabold text-red-600 mt-2">{{ $alfaHariIni }}</p>
                </div>
            </div>

            <!-- Grid Chart -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Chart 1: Pie Chart Hari Ini -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Persentase Presensi Hari Ini</h3>
                    <div class="w-full max-w-xs mx-auto">
                        <canvas id="todayChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Bar Chart Akumulasi Overall -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Total Akumulasi Presensi</h3>
                    <div>
                        <canvas id="overallChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pengaturan Notifikasi</h3>
                <p class="mt-1 text-sm text-gray-600">Aktifkan agar Anda tidak lupa melakukan absensi setiap hari.</p>
                
                <div class="mt-4">
                    <button onclick="subscribeUser()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">
                        🔔 Aktifkan Push Notification
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Pure JavaScript (Tanpa ada kode Blade/At `@` di dalamnya) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('dashboard-data');

            const hadirHariIni = parseInt(el.dataset.hadir) || 0;
            const sakitHariIni = parseInt(el.dataset.sakit) || 0;
            const izinHariIni  = parseInt(el.dataset.izin) || 0;
            const alfaHariIni  = parseInt(el.dataset.alfa) || 0;

            const totalHadir = parseInt(el.dataset.totalHadir) || 0;
            const totalSakit = parseInt(el.dataset.totalSakit) || 0;
            const totalIzin  = parseInt(el.dataset.totalIzin) || 0;
            const totalAlfa  = parseInt(el.dataset.totalAlfa) || 0;

            // Data Pie Chart (Hari Ini)
            const ctxToday = document.getElementById('todayChart').getContext('2d');
            new Chart(ctxToday, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                    datasets: [{
                        data: [hadirHariIni, sakitHariIni, izinHariIni, alfaHariIni],
                        backgroundColor: ['#22c55e', '#eab308', '#3b82f6', '#ef4444']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Data Bar Chart (Akumulasi Total)
            const ctxOverall = document.getElementById('overallChart').getContext('2d');
            new Chart(ctxOverall, {
                type: 'bar',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                    datasets: [{
                        label: 'Jumlah Presensi',
                        data: [totalHadir, totalSakit, totalIzin, totalAlfa],
                        backgroundColor: ['#22c55e', '#eab308', '#3b82f6', '#ef4444']
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
</x-app-layout>