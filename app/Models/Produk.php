<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produk';
    protected $guarded = [];

    protected $casts = [
        'harga_default' => 'decimal:2',
        'aktif'         => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(FakturItem::class, 'produk_id');
    }
}
