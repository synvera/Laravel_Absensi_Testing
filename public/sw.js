self.addEventListener('push', function (event) {
    if (!(self.registration && self.registration.showNotification)) {
        return;
    }

    let data = {};
    if (event.data) {
        data = event.data.json();
    }

    const title = data.title || 'Pengingat Absensi';
    const options = {
        body: data.body || 'Jangan lupa absen hari ini!',
        icon: '/favicon.ico', // Bisa diganti path icon aplikasi Anda
    };

    event.waitUntil(self.registration.showNotification(title, options));
});