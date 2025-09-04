<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';
    protected $guarded = [];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'metode_pembayaran_id');
    }
}
