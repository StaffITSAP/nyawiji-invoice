<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;

    protected $table = 'pajak';
    protected $guarded = [];

    protected $casts = [
        'nilai'   => 'decimal:4',
        'default' => 'boolean',
    ];

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'pajak_id');
    }

    public function items()
    {
        return $this->hasMany(FakturItem::class, 'pajak_id');
    }
}
