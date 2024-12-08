<?php

namespace App\Filament\Resources\CostResource\Pages;

use App\Filament\Resources\CostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCost extends CreateRecord
{
    protected static string $resource = CostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_price'] = $data['price'] * $data['qty'];
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
