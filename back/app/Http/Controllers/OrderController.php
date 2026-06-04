<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::with('items', 'payment')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function show(Order $order): JsonResponse
    {
        $order->load('items', 'payment');

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function store(): JsonResponse
    {
        $validated = request()->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.menu_variant_id' => 'nullable|exists:menu_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $subtotal = 0;

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);
                $variant = null;

                if (!empty($itemData['menu_variant_id'])) {
                    $variant = MenuVariant::findOrFail($itemData['menu_variant_id']);

                    if ((int) $variant->menu_item_id !== (int) $menuItem->id) {
                        throw ValidationException::withMessages([
                            'items' => 'Selected variant does not belong to the selected menu item.',
                        ]);
                    }
                }

                $quantity = (int) $itemData['quantity'];
                $unitPrice = $variant ? $variant->price : $menuItem->base_price;
                $lineTotal = $quantity * (float) $unitPrice;
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'menu_variant_id' => $variant?->id,
                    'item_name' => $menuItem->name,
                    'variant_name' => $variant?->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            $discount = (float) ($validated['discount_amount'] ?? 0);
            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total_amount' => max($subtotal - $discount, 0),
            ]);

            return $order->load('items', 'payment');
        });

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order,
        ], 201);
    }

    public function pay(Order $order): JsonResponse
    {
        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order is already paid',
            ], 422);
        }

        $validated = request()->validate([
            'method' => 'required|string|in:cash,gcash,card',
            'amount' => 'nullable|numeric|min:0',
            'cash_received' => 'nullable|numeric|min:0',
            'provider' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ]);

        $order = DB::transaction(function () use ($order, $validated) {
            $amount = (float) ($validated['amount'] ?? $order->total_amount);
            $cashReceived = $validated['cash_received'] ?? null;
            $changeAmount = null;

            if ($validated['method'] === 'cash') {
                $cashReceived = $cashReceived ?? $amount;

                if ((float) $cashReceived < $amount) {
                    throw ValidationException::withMessages([
                        'cash_received' => 'Cash received must be greater than or equal to the order total.',
                    ]);
                }

                $changeAmount = (float) $cashReceived - $amount;
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $validated['method'],
                'status' => 'paid',
                'amount' => $amount,
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
                'provider' => $validated['provider'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'transaction_id' => $validated['transaction_id'] ?? null,
                'paid_at' => now(),
            ]);

            $order->update([
                'payment_status' => 'paid',
                'receipt_number' => $this->generateReceiptNumber(),
                'paid_at' => now(),
            ]);

            return $order->load('items', 'payment');
        });

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'data' => $order,
        ]);
    }

    public function updateStatus(Order $order): JsonResponse
    {
        $validated = request()->validate([
            'status' => 'required|string|in:pending,preparing,ready,completed,cancelled',
        ]);

        $updates = ['status' => $validated['status']];

        if ($validated['status'] === 'completed') {
            $updates['completed_at'] = now();
        }

        if ($validated['status'] === 'cancelled') {
            $updates['cancelled_at'] = now();
        }

        $order->update($updates);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order->load('items', 'payment'),
        ]);
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    private function generateReceiptNumber(): string
    {
        do {
            $number = 'RCT-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));
        } while (Order::where('receipt_number', $number)->exists());

        return $number;
    }
}