<?php

namespace App\Filament\Resources\SyaratKetentuans;

use App\Filament\Resources\SyaratKetentuans\Pages\CreateSyaratKetentuan;
use App\Filament\Resources\SyaratKetentuans\Pages\EditSyaratKetentuan;
use App\Filament\Resources\SyaratKetentuans\Pages\ListSyaratKetentuans;
use App\Filament\Resources\SyaratKetentuans\Schemas\SyaratKetentuanForm;
use App\Filament\Resources\SyaratKetentuans\Tables\SyaratKetentuansTable;
use App\Models\SyaratKetentuan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SyaratKetentuanResource extends Resource
{
    protected static ?string $model = SyaratKetentuan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Syarat Ketentuan';

    public static function form(Schema $schema): Schema
    {
        return SyaratKetentuanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SyaratKetentuansTable::configure($table);
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
            'index' => ListSyaratKetentuans::route('/'),
            'create' => CreateSyaratKetentuan::route('/create'),
            'edit' => EditSyaratKetentuan::route('/{record}/edit'),
        ];
    }
}
