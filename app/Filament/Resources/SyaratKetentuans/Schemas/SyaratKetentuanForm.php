<?php

namespace App\Filament\Resources\SyaratKetentuans\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SyaratKetentuanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('judul')->required()->maxLength(255),
                    Select::make('bahasa')->options(['id' => 'Indonesia', 'en' => 'English'])->default('id'),
                    RichEditor::make('isi')->required()->columnSpanFull(),
                    Toggle::make('aktif')->default(true),
                ])->columns(2),
            ]);
    }
}
