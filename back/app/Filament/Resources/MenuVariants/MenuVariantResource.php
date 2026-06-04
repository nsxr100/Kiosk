<?php

namespace App\Filament\Resources\MenuVariants;

use App\Filament\Resources\MenuVariants\Pages\CreateMenuVariant;
use App\Filament\Resources\MenuVariants\Pages\EditMenuVariant;
use App\Filament\Resources\MenuVariants\Pages\ListMenuVariants;
use App\Filament\Resources\MenuVariants\Schemas\MenuVariantForm;
use App\Filament\Resources\MenuVariants\Tables\MenuVariantsTable;
use App\Models\MenuVariant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MenuVariantResource extends Resource
{
    protected static ?string $model = MenuVariant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MenuVariantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenuVariantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenuVariants::route('/'),
            'create' => CreateMenuVariant::route('/create'),
            'edit' => EditMenuVariant::route('/{record}/edit'),
        ];
    }
}
