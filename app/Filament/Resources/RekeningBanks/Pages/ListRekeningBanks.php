<?php

namespace App\Filament\Resources\RekeningBanks\Pages;

use App\Filament\Resources\RekeningBanks\RekeningBankResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRekeningBanks extends ListRecords
{
    protected static string $resource = RekeningBankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
