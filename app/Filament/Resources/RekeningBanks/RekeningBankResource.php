<?php

namespace App\Filament\Resources\RekeningBanks;

use App\Filament\Resources\RekeningBanks\Pages\CreateRekeningBank;
use App\Filament\Resources\RekeningBanks\Pages\EditRekeningBank;
use App\Filament\Resources\RekeningBanks\Pages\ListRekeningBanks;
use App\Filament\Resources\RekeningBanks\Schemas\RekeningBankForm;
use App\Filament\Resources\RekeningBanks\Tables\RekeningBanksTable;
use App\Models\RekeningBank;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RekeningBankResource extends Resource
{
    protected static ?string $model = RekeningBank::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Rekening Bank';

    public static function form(Schema $schema): Schema
    {
        return RekeningBankForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RekeningBanksTable::configure($table);
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
            'index' => ListRekeningBanks::route('/'),
            'create' => CreateRekeningBank::route('/create'),
            'edit' => EditRekeningBank::route('/{record}/edit'),
        ];
    }
}
