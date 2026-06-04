<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Support\MenuImageData;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuItem extends CreateRecord
{
    protected static string $resource = MenuItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $dataUrl = MenuImageData::fromPublicPath($data['image_url'] ?? null);

        if ($dataUrl) {
            $data['image_data_url'] = $dataUrl;
        }

        return $data;
    }
}
