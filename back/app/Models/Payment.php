<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'status',
        'amount',
        'cash_received',
        'change_amount',
        'provider',
        'reference_number',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}