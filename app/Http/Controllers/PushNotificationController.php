<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- Tambahkan Facade Auth di atas

class PushNotificationController extends Controller
{
    public function store(Request $request)
        {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $user->pushSubscriptions()->updateOrCreate(
                ['endpoint' => $request->endpoint],
                [
                    'publicKey' => $request->keys['p256dh'] ?? null,
                    'authToken' => $request->keys['auth'] ?? null,
                    'contentEncoding' => $request->supportedEncodings[0] ?? 'aesgcm',
                ]
            );

            return response()->json(['success' => true]);
        }
}