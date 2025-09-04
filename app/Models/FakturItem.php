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

    // ===== Relasi =====
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

    // ===== Logika hitung =====
    public function hitungJumlah(): float
    {
        $harga = (float) $this->harga_satuan * (float) $this->kuantitas;

        $potItem = 0.0;
        if ($this->diskon_id) {
            $disk = $this->relationLoaded('diskon') ? $this->diskon : Diskon::find($this->diskon_id);
            if ($disk) {
                $potItem = $disk->tipe === 'persentase'
                    ? $harga * ((float) $disk->nilai / 100)
                    : (float) $disk->nilai;
            }
        }
        $setelahDiskon = max($harga - $potItem, 0.0);

        $pajakItem = 0.0;
        if ($this->pajak_id) {
            $pj = $this->relationLoaded('pajak') ? $this->pajak : Pajak::find($this->pajak_id);
            if ($pj) {
                $pajakItem = $pj->tipe === 'persentase'
                    ? $setelahDiskon * ((float) $pj->nilai / 100)
                    : (float) $pj->nilai;
            }
        }

        return $setelahDiskon + $pajakItem;
    }

    public function sinkronJumlah(): void
    {
        $this->jumlah = $this->hitungJumlah();
    }

    protected static function booted(): void
    {
        // Pastikan jumlah tidak 0/NULL setiap kali item disimpan dari mana pun
        static::saving(function (FakturItem $i) {
            $i->sinkronJumlah();
        });
    }
}
