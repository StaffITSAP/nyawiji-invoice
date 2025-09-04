<?php

namespace App\Filament\Resources\MetodePembayarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MetodePembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('nama')->required()->maxLength(100),
                    Toggle::make('aktif')->default(true),
                ])->columns(2),
            ]);
    }
}
