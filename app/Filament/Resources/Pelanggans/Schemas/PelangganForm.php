<?php

namespace App\Filament\Resources\Pelanggans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PelangganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('nama')->required()->maxLength(255),
                    TextInput::make('email')->email()->maxLength(255),
                    TextInput::make('telepon')->maxLength(50),
                    TextInput::make('npwp')->maxLength(64),
                    Textarea::make('alamat_tagihan')->rows(4),
                ])->columns(2),
            ]);
    }
}
