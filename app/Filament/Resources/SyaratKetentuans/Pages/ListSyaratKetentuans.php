<?php

namespace App\Filament\Resources\SyaratKetentuans\Pages;

use App\Filament\Resources\SyaratKetentuans\SyaratKetentuanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSyaratKetentuans extends ListRecords
{
    protected static string $resource = SyaratKetentuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
