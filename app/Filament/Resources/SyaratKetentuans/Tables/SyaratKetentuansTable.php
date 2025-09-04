<?php

namespace App\Filament\Resources\SyaratKetentuans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SyaratKetentuansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->searchable()->sortable(),
                TextColumn::make('bahasa')->badge()->sortable(),
                ToggleColumn::make('aktif')->label('Aktif'),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Dibuat'),
            ])
            ->filters([
                TernaryFilter::make('aktif')->label('Aktif')->nullable()->boolean(),

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
