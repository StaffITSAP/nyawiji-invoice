<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Faktur {{ $faktur->nomor }}</title>

    @php
    // Tema dengan fallback aman untuk DomPDF
    $warnaUtama = $pengaturan['warna_utama'] ?? '#0ea5e9';
    $warnaTeks = $pengaturan['warna_teks'] ?? '#111827';
    $fontFamily = $pengaturan['font_family'] ?? 'DejaVu Sans, sans-serif';
    $fmtTanggal = $pengaturan['format_tanggal'] ?? 'd M Y';

    // Atribut style yang akan disisipkan utuh via echo (hindari {{ }} dalam CSS)
    $bodyStyle = 'style="font-family: ' . htmlspecialchars($fontFamily, ENT_QUOTES, 'UTF-8')
    . '; color: ' . htmlspecialchars($warnaTeks, ENT_QUOTES, 'UTF-8') . ';"';
    $h1Style = 'style="color: ' . htmlspecialchars($warnaUtama, ENT_QUOTES, 'UTF-8') . ';"';
    $pillStyle = 'style="margin-top:6px; background: ' . htmlspecialchars($warnaUtama, ENT_QUOTES, 'UTF-8') . '; color:#fff;"';
    @endphp

    <style>
        /* Hanya CSS statis di sini (tanpa Blade/variabel dinamis) */
        @page {
            margin: 28mm 18mm;
        }

        body {
            font-size: 11px;
        }

        .brand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .brand h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: .5px;
        }

        .muted {
            color: #6b7280;
        }

        .pill {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 10px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .totals td {
            border: none;
            padding: 6px 8px;
        }

        .footer {
            margin-top: 14px;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body <?php echo $bodyStyle; ?>>

    <div class="brand">
        <div>
            <h1 <?php echo $h1Style; ?>>FAKTUR</h1>
            <div class="muted">{{ $pengaturan['nama_perusahaan'] ?? 'Perusahaan' }}</div>
            @if(!empty($pengaturan['alamat_perusahaan']))
            <div class="muted">{{ $pengaturan['alamat_perusahaan'] }}</div>
            @endif
            @if(!empty($pengaturan['email_perusahaan']))
            <div class="muted">{{ $pengaturan['email_perusahaan'] }}</div>
            @endif
        </div>
        <div style="text-align:right">
            @if(!empty($pengaturan['logo_path']))
            <img src="{{ public_path('storage/'.$pengaturan['logo_path']) }}" style="height:50px;" />
            @endif
            <div class="pill" <?php echo $pillStyle; ?>>
                {{ strtoupper($faktur->status) }}
            </div>
        </div>
    </div>

    <table style="margin-bottom:10px">
        <tr>
            <td>
                <strong>Kepada:</strong><br>
                {{ $faktur->pelanggan->nama }}<br>
                {!! nl2br(e($faktur->pelanggan->alamat_tagihan)) !!}
            </td>
            <td class="right">
                <strong>Nomor:</strong> {{ $faktur->nomor ?? 'DRAFT' }}<br>
                <strong>Tanggal:</strong> {{ $faktur->tanggal->format($fmtTanggal) }}<br>
                @if($faktur->jatuh_tempo)
                <strong>Jatuh Tempo:</strong> {{ $faktur->jatuh_tempo->format($fmtTanggal) }}<br>
                @endif
                <strong>Mata Uang:</strong> {{ $faktur->kode_mata_uang }}
                (kurs {{ number_format($faktur->kurs, 2, ',', '.') }})
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width:42%">Deskripsi</th>
                <th class="right" style="width:9%">Qty</th>
                <th style="width:10%">Satuan</th>
                <th class="right" style="width:14%">Harga</th>
                <th class="right" style="width:10%">Diskon</th>
                <th class="right" style="width:10%">Pajak</th>
                <th class="right" style="width:15%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faktur->items as $i)
            <tr>
                <td>{{ $i->deskripsi }}</td>
                <td class="right">{{ number_format($i->kuantitas, 2, ',', '.') }}</td>
                <td>{{ $i->satuan }}</td>
                <td class="right">{{ number_format($i->harga_satuan, 2, ',', '.') }}</td>
                <td class="right">
                    @if($i->diskon)
                    {{ $i->diskon->tipe === 'persentase'
                                ? $i->diskon->nilai.' %'
                                : number_format($i->diskon->nilai, 2, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
                <td class="right">
                    @if($i->pajak)
                    {{ $i->pajak->tipe === 'persentase'
                                ? $i->pajak->nilai.' %'
                                : number_format($i->pajak->nilai, 2, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
                <td class="right">{{ number_format($i->jumlah, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="margin-top:10px">
        <tr>
            <td style="width:55%"></td>
            <td style="width:45%">
                <table class="totals" style="width:100%">
                    <tr>
                        <td>Subtotal</td>
                        <td class="right">{{ number_format($faktur->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Diskon</td>
                        <td class="right">- {{ number_format($faktur->total_diskon, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Pajak</td>
                        <td class="right">{{ number_format($faktur->total_pajak, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="right"><strong>{{ number_format($faktur->total_bersih, 2, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($faktur->syarat->count())
    <div style="margin-top:14px">
        <strong>Syarat &amp; Ketentuan</strong>
        <ol>
            @foreach($faktur->syarat as $s)
            <li><strong>{{ $s->judul }}</strong> — {!! $s->isi !!}</li>
            @endforeach
        </ol>
    </div>
    @endif

    @if(!empty($pengaturan['footer_html']))
    <div class="footer">{!! $pengaturan['footer_html'] !!}</div>
    @endif
</body>

</html>