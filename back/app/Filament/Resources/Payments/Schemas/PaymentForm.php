<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                TextInput::make('method')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('cash_received')
                    ->numeric(),
                TextInput::make('change_amount')
                    ->numeric(),
                TextInput::make('provider'),
                TextInput::make('reference_number'),
                TextInput::make('transaction_id'),
                DateTimePicker::make('paid_at'),
            ]);
    }
}
