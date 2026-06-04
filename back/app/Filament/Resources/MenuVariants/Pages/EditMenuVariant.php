<?php

namespace App\Filament\Resources\MenuVariants\Pages;

use App\Filament\Resources\MenuVariants\MenuVariantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMenuVariant extends EditRecord
{
    protected static string $resource = MenuVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
