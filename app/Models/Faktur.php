<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FakturSyarat;
use App\Models\SyaratKetentuan;

class Faktur extends Model
{
    use SoftDeletes;

    protected $table = 'faktur';
    protected $guarded = [];

    protected $casts = [
        'tanggal'      => 'date',
        'jatuh_tempo'  => 'date',
        'meta'         => 'array',
        'subtotal'     => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'total_pajak'  => 'decimal:2',
        'total_bersih' => 'decimal:2',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
    public function items()
    {
        return $this->hasMany(FakturItem::class, 'faktur_id')->orderBy('urutan');
    }
    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'pajak_id');
    }
    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'diskon_id');
    }
    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }
    public function rekeningBank()
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_bank_id');
    }

    public function syaratPivot()
    {
        return $this->hasMany(FakturSyarat::class, 'faktur_id')->orderBy('urutan');
    }

    public function syarat()
    {
        return $this->belongsToMany(SyaratKetentuan::class, 'faktur_syarat')
            ->withPivot('urutan')
            ->withTimestamps()
            ->orderBy('faktur_syarat.urutan');
    }

    /**
     * Hitung subtotal, diskon, pajak, dan total_bersih berdasarkan items + pajak/diskon faktur.
     */
    public function hitungTotal(): void
    {
        $subtotal = 0;
        foreach ($this->items as $i) {
            $harga = (float) $i->harga_satuan * (float) $i->kuantitas;

            $potItem = 0;
            if ($i->diskon) {
                $potItem = $i->diskon->tipe === 'persentase'
                    ? $harga * ((float)$i->diskon->nilai / 100)
                    : (float) $i->diskon->nilai;
            }

            $setelahDiskonItem = max($harga - $potItem, 0);

            $pajakItem = 0;
            if ($i->pajak) {
                $pajakItem = $i->pajak->tipe === 'persentase'
                    ? $setelahDiskonItem * ((float)$i->pajak->nilai / 100)
                    : (float) $i->pajak->nilai;
            }

            // set nilai jumlah di object (akan disave oleh caller)
            $i->jumlah = $setelahDiskonItem + $pajakItem;
            $subtotal += $setelahDiskonItem;
        }

        $this->subtotal = $subtotal;

        // Diskon faktur
        $diskonFaktur = 0;
        if ($this->diskon) {
            $diskonFaktur = $this->diskon->tipe === 'persentase'
                ? $subtotal * ((float)$this->diskon->nilai / 100)
                : (float) $this->diskon->nilai;
        }
        $setelahDiskonFaktur = max($subtotal - $diskonFaktur, 0);

        // Pajak faktur
        $pajakFaktur = 0;
        if ($this->pajak) {
            $pajakFaktur = $this->pajak->tipe === 'persentase'
                ? $setelahDiskonFaktur * ((float)$this->pajak->nilai / 100)
                : (float) $this->pajak->nilai;
        }

        // Akumulasi diskon & pajak level item
        $totalDiskonItem = 0;
        $totalPajakItem  = 0;
        foreach ($this->items as $i) {
            $harga = (float) $i->harga_satuan * (float) $i->kuantitas;
            $potItem = 0;
            if ($i->diskon) {
                $potItem = $i->diskon->tipe === 'persentase'
                    ? $harga * ((float)$i->diskon->nilai / 100)
                    : (float) $i->diskon->nilai;
            }
            $totalDiskonItem += $potItem;

            $setelahDiskonItem = max($harga - $potItem, 0);
            if ($i->pajak) {
                $totalPajakItem += $i->pajak->tipe === 'persentase'
                    ? $setelahDiskonItem * ((float)$i->pajak->nilai / 100)
                    : (float) $i->pajak->nilai;
            }
        }

        $this->total_diskon = $diskonFaktur + $totalDiskonItem;
        $this->total_pajak  = $pajakFaktur + $totalPajakItem;

        // Total akhir: setelah diskon faktur + pajak faktur + jumlah item (yang sudah termasuk pajak item)
        $this->total_bersih = $setelahDiskonFaktur + $pajakFaktur + $this->items->sum('jumlah');
    }

    /**
     * Helper aman: sinkronkan jumlah setiap item, hitung total faktur, dan simpan senyap.
     */
    public function refreshTotalsAndSave(): void
    {
        $this->load(['items.diskon', 'items.pajak', 'diskon', 'pajak']);

        // pastikan setiap item menyimpan 'jumlah' terbaru
        foreach ($this->items as $i) {
            $i->sinkronJumlah();
            $i->saveQuietly();
        }

        // hitung & simpan total faktur
        $this->hitungTotal();
        $this->saveQuietly();
    }
}
