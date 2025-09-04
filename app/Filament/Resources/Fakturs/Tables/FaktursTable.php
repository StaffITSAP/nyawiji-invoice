<?php

namespace App\Filament\Resources\Fakturs\Tables;

use App\Models\Faktur;
use App\Models\PengaturanInvoice;
use App\Services\PenomoranService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FaktursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor')->label('No.')->searchable()->sortable(),
                TextColumn::make('pelanggan.nama')->label('Pelanggan')->searchable(),
                TextColumn::make('tanggal')->date('d M Y')->sortable(),
                TextColumn::make('total_bersih')->money('IDR')->sortable()->label('Total'),
                BadgeColumn::make('status')->colors([
                    'warning' => 'draft',
                    'info'    => 'terbit',
                    'success' => 'lunas',
                    'danger'  => 'batal',
                ]),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'terbit' => 'Terbit',
                    'lunas' => 'Lunas',
                    'batal' => 'Batal'
                ])
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('terbitkan')
                    ->label('Terbitkan')
                    ->icon('heroicon-o-check-badge')
                    ->requiresConfirmation()
                    ->visible(fn(Faktur $r) => $r->status === 'draft')
                    ->authorize(fn() => auth()->user()?->can('terbitkan faktur') ?? true)
                    ->action(function (Faktur $record) {
                        $pengaturan = PengaturanInvoice::first();
                        $format     = $pengaturan->format_nomor ?? '{PREFIX}/{YYYY}/{MM}/{COUNTER:4}';
                        $prefix     = $pengaturan->prefix_nomor ?? 'NWS';

                        $record->nomor  = PenomoranService::berikutnya('faktur', $format, $prefix, $record->tanggal);
                        $record->status = 'terbit';
                        $record->meta   = ['pengaturan' => $pengaturan?->toArray()];
                        $record->saveQuietly();

                        // pastikan jumlah item & total faktur mutakhir
                        $record->refreshTotalsAndSave();

                        Notification::make()->title('Faktur diterbitkan')->success()->send();
                    }),
                Action::make('pdf')
                    ->label('PDF')->icon('heroicon-o-arrow-down-tray')
                    ->url(fn(Faktur $r) => route('faktur.pdf', $r))
                    ->openUrlInNewTab(),
                DeleteBulkAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
