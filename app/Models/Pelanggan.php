<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pelanggan';
    protected $guarded = [];

    protected $casts = [
        // tambahkan cast jika diperlukan
    ];

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'pelanggan_id');
    }
}
