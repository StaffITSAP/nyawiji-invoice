<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturItem extends Model
{
    use HasFactory;

    protected $table = 'faktur_item';
    protected $guarded = [];

    protected $casts = [
        'kuantitas'    => 'decimal:4',
        'harga_satuan' => 'decimal:2',
        'jumlah'       => 'decimal:2',
    ];

    public function faktur()
    {
        return $this->belongsTo(Faktur::class, 'faktur_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'pajak_id');
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'diskon_id');
    }
}
