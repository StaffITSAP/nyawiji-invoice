<?php

namespace App\Filament\Resources\Fakturs\Schemas;

use App\Models\PengaturanInvoice;
use App\Models\Produk;
use App\Models\SyaratKetentuan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FakturForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Utama')->schema([
                    Select::make('pelanggan_id')
                        ->label('Pelanggan')->relationship('pelanggan', 'nama')
                        ->searchable()->preload()->required(),

                    Grid::make(3)->schema([
                        DatePicker::make('tanggal')->required()->default(now()),
                        DatePicker::make('jatuh_tempo'),
                        Select::make('status')->options([
                            'draft' => 'Draft',
                            'terbit' => 'Terbit',
                            'lunas' => 'Lunas',
                            'batal' => 'Batal'
                        ])->default('draft'),
                    ]),

                    Grid::make(3)->schema([
                        TextInput::make('kode_mata_uang')->maxLength(10)
                            ->default(fn() => optional(PengaturanInvoice::first())->kode_mata_uang ?? 'IDR'),
                        TextInput::make('kurs')->numeric()->default(1)->step('0.000001'),
                        TextInput::make('nomor')->disabled()->helperText('Diisi otomatis ketika Terbit.'),
                    ]),

                    Grid::make(3)->schema([
                        Select::make('pajak_id')->label('Pajak Faktur')->relationship('pajak', 'nama')->preload(),
                        Select::make('diskon_id')->label('Diskon Faktur')->relationship('diskon', 'nama')->preload(),
                        Select::make('metode_pembayaran_id')->label('Metode Pembayaran')->relationship('metodePembayaran', 'nama')->preload(),
                    ]),
                    Select::make('rekening_bank_id')->label('Rekening Bank')->relationship('rekeningBank', 'nama_rekening')->preload(),
                ])->columns(2),

                Section::make('Item')->schema([
                    Repeater::make('items')->relationship()
                        ->orderable('urutan')
                        ->defaultItems(1)
                        ->createItemButtonLabel('Tambah Item')
                        ->schema([
                            Grid::make(12)->schema([
                                Select::make('produk_id')->label('Produk')->relationship('produk', 'nama')->preload()->columnSpan(4)
                                    ->searchable()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $p = Produk::find($state);
                                            if ($p) {
                                                $set('harga_satuan', $p->harga_default);
                                                $set('satuan', $p->satuan);
                                                $set('deskripsi', $p->nama);
                                            }
                                        }
                                    }),
                                TextInput::make('deskripsi')->required()->columnSpan(8),
                                TextInput::make('kuantitas')->numeric()->default(1)->step('0.0001')->columnSpan(2),
                                TextInput::make('satuan')->maxLength(50)->columnSpan(2),
                                TextInput::make('harga_satuan')->numeric()->default(0)->step('0.01')->prefix('Rp')->columnSpan(3),
                                Select::make('diskon_id')->relationship('diskon', 'nama')->columnSpan(2),
                                Select::make('pajak_id')->relationship('pajak', 'nama')->columnSpan(2),
                                TextInput::make('jumlah')
                                    ->label('Jumlah (auto)')
                                    ->numeric()
                                    ->disabled()            // tampil read-only
                                    ->dehydrated(false)     // JANGAN kirim ke DB dari form
                                    ->default(0)            // tampilan awal 0
                                    ->columnSpan(3)
                                    ->prefix('Rp')
                                    ->helperText('Otomatis dihitung saat simpan'),

                            ]),
                        ]),
                ])->collapsible(),

                Section::make('Syarat & Ketentuan')->schema([
                    // KUNCI: pakai relasi 'syaratPivot' (hasMany ke tabel pivot)
                    Repeater::make('syaratPivot')
                        ->relationship('syaratPivot')   // <— penting!
                        ->reorderable('urutan')
                        ->schema([
                            Select::make('syarat_ketentuan_id')
                                ->label('Pilih Syarat')
                                ->options(fn() => SyaratKetentuan::where('aktif', 1)->pluck('judul', 'id'))
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('urutan')->numeric()->default(0),
                        ])
                        ->defaultItems(0),

                    RichEditor::make('catatan')
                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link', 'undo', 'redo']),
                ])->collapsed(),
            ]);
    }
}
