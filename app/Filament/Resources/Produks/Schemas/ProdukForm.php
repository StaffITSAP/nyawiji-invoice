<?php

namespace App\Filament\Resources\Produks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('nama')->required()->maxLength(255),
                    Textarea::make('deskripsi')->rows(3),
                    TextInput::make('satuan')->default('unit')->maxLength(50),
                    TextInput::make('harga_default')->numeric()->step('0.01')->default(0)->prefix('Rp'),
                    Toggle::make('aktif')->default(true)->inline(false),
                ])->columns(2),
            ]);
    }
}
