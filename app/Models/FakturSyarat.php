<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FakturSyarat extends Model
{
    protected $table = 'faktur_syarat';
    protected $guarded = [];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function faktur()
    {
        return $this->belongsTo(Faktur::class, 'faktur_id');
    }

    public function syaratKetentuan()
    {
        return $this->belongsTo(SyaratKetentuan::class, 'syarat_ketentuan_id');
    }
}
