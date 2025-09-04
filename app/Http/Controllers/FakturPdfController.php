<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use Barryvdh\DomPDF\Facade\Pdf;

class FakturPdfController extends Controller
{
    public function unduh(Faktur $faktur)
    {
        // Pastikan jumlah item & total faktur sudah benar
        $faktur->refreshTotalsAndSave();

        $faktur->load([
            'pelanggan',
            'items.produk',
            'items.pajak',
            'items.diskon',
            'pajak',
            'diskon',
            'rekeningBank',
            'metodePembayaran',
            'syarat'
        ]);

        $pengaturan = optional($faktur->meta)['pengaturan']
            ?? \App\Models\PengaturanInvoice::first()?->toArray();

        $pdf = Pdf::loadView('pdf.faktur', [
            'faktur'     => $faktur,
            'pengaturan' => $pengaturan,
        ])->setPaper('a4');

        return $pdf->stream("Faktur-{$faktur->nomor}.pdf");
    }
}
