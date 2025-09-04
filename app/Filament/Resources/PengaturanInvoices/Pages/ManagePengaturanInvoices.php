<?php

namespace App\Filament\Resources\PengaturanInvoices\Pages;

use App\Filament\Resources\PengaturanInvoices\PengaturanInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePengaturanInvoices extends ManageRecords
{
    protected static string $resource = PengaturanInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
