<?php

namespace App\Filament\Resources\Pajaks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PajakForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('nama')->required(),
                    Select::make('tipe')->options([
                        'persentase' => 'Persentase (%)',
                        'nominal'   => 'Nominal (Rp)',
                    ])->default('persentase')->required(),
                    TextInput::make('nilai')->numeric()->step('0.0001')->default(0),
                    Toggle::make('default')->label('Default')->default(false),
                ])->columns(2),
            ]);
    }
}
