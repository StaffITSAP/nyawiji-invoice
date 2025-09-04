<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penomoran extends Model
{
    use HasFactory;

    protected $table = 'penomoran';
    protected $guarded = [];

    protected $casts = [
        'tahun'   => 'integer',
        'bulan'   => 'integer',
        'counter' => 'integer',
    ];
}
