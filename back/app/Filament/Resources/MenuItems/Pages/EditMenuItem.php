<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Support\MenuImageData;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMenuItem extends EditRecord
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $dataUrl = MenuImageData::fromPublicPath($data['image_url'] ?? null);

        if ($dataUrl) {
            $data['image_data_url'] = $dataUrl;
        }

        return $data;
    }
}
