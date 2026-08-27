<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
<script>
    // Daftarkan Service Worker
    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.register('/sw.js')
        .then(function(swReg) {
            console.log('Service Worker terdaftar');
        })
        .catch(function(error) {
            console.error('Service Worker gagal:', error);
        });
    }

    // Fungsi untuk mengaktifkan push notification saat tombol diklik
    function subscribeUser() {
        navigator.serviceWorker.ready.then(function(swReg) {
            const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY') }}";
            const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

            swReg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: convertedVapidKey
            })
            .then(function(subscription) {
                // Kirim data ke backend Laravel
                fetch('/api/save-subscription', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(subscription)
                })
                .then(res => res.json())
                .then(data => {
                    alert('Notifikasi berhasil diaktifkan!');
                });
            })
            .catch(function(err) {
                console.error('Gagal berlangganan push:', err);
                alert('Gagal mengaktifkan notifikasi. Pastikan izin browser diizinkan.');
            });
        });
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
</script>
</html>
