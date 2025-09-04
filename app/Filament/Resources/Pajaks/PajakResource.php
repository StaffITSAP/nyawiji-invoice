<?php

namespace App\Filament\Resources\Pajaks;

use App\Filament\Resources\Pajaks\Pages\CreatePajak;
use App\Filament\Resources\Pajaks\Pages\EditPajak;
use App\Filament\Resources\Pajaks\Pages\ListPajaks;
use App\Filament\Resources\Pajaks\Schemas\PajakForm;
use App\Filament\Resources\Pajaks\Tables\PajaksTable;
use App\Models\Pajak;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PajakResource extends Resource
{
    protected static ?string $model = Pajak::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Pajak';

    public static function form(Schema $schema): Schema
    {
        return PajakForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PajaksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPajaks::route('/'),
            'create' => CreatePajak::route('/create'),
            'edit' => EditPajak::route('/{record}/edit'),
        ];
    }
}
