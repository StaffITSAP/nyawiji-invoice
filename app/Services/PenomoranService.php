<?php

namespace App\Services;

use App\Models\Penomoran;
use Illuminate\Support\Facades\DB;

class PenomoranService
{
    public static function berikutnya(string $konteks, string $format, string $prefix, \DateTimeInterface $tanggal): string
    {
        $tahun = (int)$tanggal->format('Y');
        $bulan = (int)$tanggal->format('m');

        return DB::transaction(function () use ($konteks, $format, $prefix, $tahun, $bulan, $tanggal) {
            // jika format pakai {MM}, kita kunci per bulan; kalau tidak, null
            $pakaiBulan = str_contains($format, '{MM}') || str_contains($format, '{M}');
            $row = Penomoran::lockForUpdate()->firstOrCreate(
                ['konteks' => $konteks, 'prefix' => $prefix, 'tahun' => $tahun, 'bulan' => $pakaiBulan ? $bulan : null],
                ['counter' => 0]
            );
            $row->counter++;
            $row->save();

            $counter = $row->counter;

            $replace = [
                '{PREFIX}'     => $prefix,
                '{YYYY}'       => $tanggal->format('Y'),
                '{YY}'         => $tanggal->format('y'),
                '{MM}'         => $tanggal->format('m'),
                '{M}'          => $tanggal->format('n'),
            ];

            // {COUNTER:4} / {COUNTER}
            $nomor = preg_replace_callback('/\{COUNTER(?::(\d+))?\}/', function($m) use ($counter){
                $pad = isset($m[1]) ? (int)$m[1] : 1;
                return str_pad((string)$counter, max(1, $pad), '0', STR_PAD_LEFT);
            }, $format);

            return strtr($nomor, $replace);
        });
    }
}
