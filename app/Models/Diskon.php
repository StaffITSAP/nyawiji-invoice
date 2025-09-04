<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;

    protected $table = 'diskon';
    protected $guarded = [];

    protected $casts = [
        'nilai'   => 'decimal:4',
        'default' => 'boolean',
    ];

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'diskon_id');
    }

    public function items()
    {
        return $this->hasMany(FakturItem::class, 'diskon_id');
    }
}
