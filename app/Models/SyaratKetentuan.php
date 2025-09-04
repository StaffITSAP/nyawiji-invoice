<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyaratKetentuan extends Model
{
    use HasFactory;

    protected $table = 'syarat_ketentuan';
    protected $guarded = [];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function faktur()
    {
        return $this->belongsToMany(Faktur::class, 'faktur_syarat')
            ->withPivot('urutan')
            ->withTimestamps();
    }
}
