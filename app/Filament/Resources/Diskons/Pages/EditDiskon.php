<?php

namespace App\Filament\Resources\Diskons\Pages;

use App\Filament\Resources\Diskons\DiskonResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDiskon extends EditRecord
{
    protected static string $resource = DiskonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
