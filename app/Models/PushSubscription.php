<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;

    // Izinkan kolom-kolom ini diisi secara massal
    protected $fillable = [
        'user_id',
        'endpoint',
        'publicKey',
        'authToken',
        'contentEncoding',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}