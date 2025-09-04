<?php

namespace App\Filament\Resources\Diskons\Pages;

use App\Filament\Resources\Diskons\DiskonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDiskons extends ListRecords
{
    protected static string $resource = DiskonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
