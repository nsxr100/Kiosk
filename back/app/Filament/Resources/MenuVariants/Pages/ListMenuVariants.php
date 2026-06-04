<?php

namespace App\Filament\Resources\MenuVariants\Pages;

use App\Filament\Resources\MenuVariants\MenuVariantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenuVariants extends ListRecords
{
    protected static string $resource = MenuVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
