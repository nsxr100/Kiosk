<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_number'),
                TextEntry::make('receipt_number')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('payment_status'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('tax_amount')
                    ->numeric(),
                TextEntry::make('discount_amount')
                    ->numeric(),
                TextEntry::make('total_amount')
                    ->numeric(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('paid_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('cancelled_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
