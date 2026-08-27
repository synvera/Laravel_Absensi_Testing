<?php

use Illuminate\Support\Facades\Schedule;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

Schedule::call(function () {
    $today = Carbon::today();
    
    // Cari user yang BELUM absen hari ini
    $users = User::whereDoesntHave('attendances', function($query) use ($today) {
        $query->whereDate('created_at', $today);
    })->with('pushSubscriptions')->get();

    $auth = [
        'VAPID' => [
            'subject' => env('VAPID_SUBJECT'),
            'publicKey' => env('VAPID_PUBLIC_KEY'),
            'privateKey' => env('VAPID_PRIVATE_KEY'),
        ],
    ];

    $webPush = new WebPush($auth);

    foreach ($users as $user) {
        foreach ($user->pushSubscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->publicKey,
                'authToken' => $sub->authToken,
            ]);

            // PENGATURAN ISI PESAN NOTIFIKASI DI SINI
            $payload = json_encode([
                'title' => 'Pengingat Absensi ⏰',
                'body' => 'Halo ' . $user->name . ', jangan lupa absen hari ini ya!'
            ]);

            $webPush->queueNotification($subscription, $payload);
        }
    }

    $webPush->flush();

})->dailyAt('08:10'); // <--- Diatur jam berapa pengiriman otomatisnya