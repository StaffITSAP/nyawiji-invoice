<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanInvoice extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_invoice';
    protected $guarded = [];

    protected $casts = [
        'template_opsi' => 'array',
    ];

    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'pajak_id');
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'diskon_id');
    }

    public function rekeningBank()
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_bank_id');
    }
}
