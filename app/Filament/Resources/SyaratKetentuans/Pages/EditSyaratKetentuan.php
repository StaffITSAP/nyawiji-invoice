<?php

namespace App\Filament\Resources\SyaratKetentuans\Pages;

use App\Filament\Resources\SyaratKetentuans\SyaratKetentuanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSyaratKetentuan extends EditRecord
{
    protected static string $resource = SyaratKetentuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
