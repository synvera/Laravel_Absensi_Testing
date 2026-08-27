<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendAttendanceReminder extends Command
{
    protected $signature = 'absensi:send-reminder';
    protected $description = 'Kirim pengingat push notification absensi harian';

    public function handle()
    {
        $auth = [
            'VAPID' => [
                'subject' => env('VAPID_SUBJECT'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        // Ambil semua user yang memiliki subscription notifikasi
        $users = User::has('pushSubscriptions')->get();

        foreach ($users as $user) {
            foreach ($user->pushSubscriptions as $sub) {
                $subscription = Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->publicKey,
                    'authToken' => $sub->authToken,
                ]);

                $payload = json_encode([
                    'title' => 'Pengingat Absensi!',
                    'body' => 'Halo ' . $user->name . ', yuk lakukan absensi hari ini.'
                ]);

                $webPush->queueNotification($subscription, $payload);
            }
        }

        foreach ($webPush->flush() as $report) {
            // Log atau abaikan hasil kirim
        }

        $this->info('Pengingat absensi berhasil dikirim!');
    }
}