<?php

namespace App\Filament\Resources\RekeningBanks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RekeningBankForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('bank')->required()->maxLength(100),
                    TextInput::make('nama_rekening')->required()->maxLength(255),
                    TextInput::make('nomor_rekening')->required()->maxLength(50),
                    TextInput::make('swift')->maxLength(50),
                    TextInput::make('cabang')->maxLength(100),
                    Toggle::make('default')->default(false),
                ])->columns(2),
            ]);
    }
}
