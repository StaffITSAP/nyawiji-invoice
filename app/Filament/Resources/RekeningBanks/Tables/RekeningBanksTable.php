<?php

namespace App\Filament\Resources\RekeningBanks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class RekeningBanksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank')->sortable()->searchable(),
                TextColumn::make('nama_rekening')->sortable()->searchable(),
                TextColumn::make('nomor_rekening')->sortable(),
                TextColumn::make('swift'),
                ToggleColumn::make('default')->label('Default'),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Dibuat'),
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
}
