<?php

namespace App\Filament\Resources\PengaturanInvoices;

use App\Filament\Resources\PengaturanInvoices\Pages\ManagePengaturanInvoices;
use App\Models\Diskon;
use App\Models\Pajak;
use App\Models\PengaturanInvoice;
use App\Models\RekeningBank;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PengaturanInvoiceResource extends Resource
{
    protected static ?string $model = PengaturanInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Pengaturan Invoice';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Perusahaan')->schema([
                    TextInput::make('nama_perusahaan')->required(),
                    TextInput::make('alamat_perusahaan'),
                    TextInput::make('email_perusahaan')->email(),
                    TextInput::make('telepon_perusahaan'),
                    TextInput::make('npwp_perusahaan'),
                    FileUpload::make('logo_path')->image()->directory('logo'),
                ])->columns(2),

                Section::make('Penomoran & Format')->schema([
                    TextInput::make('prefix_nomor')->default('NWS'),
                    TextInput::make('format_nomor')
                        ->default('{PREFIX}/{YYYY}/{MM}/{COUNTER:4}')
                        ->helperText('Placeholder: {PREFIX} {YYYY} {YY} {MM} {M} {COUNTER:n}'),
                    TextInput::make('format_tanggal')->default('d M Y'),
                    TextInput::make('kode_mata_uang')->default('IDR'),
                    TextInput::make('pembulatan')->numeric()->default(0),
                ])->columns(2),

                Section::make('Default Pajak/Diskon/Rekening')->schema([
                    Select::make('pajak_id')->options(Pajak::pluck('nama', 'id'))->searchable()->preload(),
                    Select::make('diskon_id')->options(Diskon::pluck('nama', 'id'))->searchable()->preload(),
                    Select::make('rekening_bank_id')->options(RekeningBank::pluck('nama_rekening', 'id'))->searchable()->preload(),
                ]),

                Section::make('Tampilan PDF')->schema([
                    ColorPicker::make('warna_utama')->default('#0ea5e9'),
                    ColorPicker::make('warna_teks')->default('#111827'),
                    TextInput::make('font_family')->default('Inter, ui-sans-serif'),
                    Textarea::make('footer_html')->rows(3),
                    KeyValue::make('template_opsi')->columnSpanFull()->keyLabel('Kunci')->valueLabel('Nilai'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_perusahaan')
                    ->searchable(),
                TextColumn::make('alamat_perusahaan')
                    ->searchable(),
                TextColumn::make('email_perusahaan')
                    ->searchable(),
                TextColumn::make('telepon_perusahaan')
                    ->searchable(),
                TextColumn::make('npwp_perusahaan')
                    ->searchable(),
                TextColumn::make('prefix_nomor')
                    ->searchable(),
                TextColumn::make('format_nomor')
                    ->searchable(),
                TextColumn::make('format_tanggal')
                    ->searchable(),
                TextColumn::make('kode_mata_uang')
                    ->searchable(),
                TextColumn::make('pembulatan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('warna_utama')
                    ->searchable(),
                TextColumn::make('warna_teks')
                    ->searchable(),
                TextColumn::make('font_family')
                    ->searchable(),
                TextColumn::make('logo_path')
                    ->searchable(),
                TextColumn::make('pajak_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('diskon_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rekening_bank_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePengaturanInvoices::route('/'),
        ];
    }
}
