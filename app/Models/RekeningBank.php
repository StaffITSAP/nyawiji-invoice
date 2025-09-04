<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningBank extends Model
{
    use HasFactory;

    protected $table = 'rekening_bank';
    protected $guarded = [];

    protected $casts = [
        'default' => 'boolean',
    ];

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'rekening_bank_id');
    }

    public function pengaturan()
    {
        return $this->hasMany(PengaturanInvoice::class, 'rekening_bank_id');
    }
}
