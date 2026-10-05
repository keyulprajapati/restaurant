<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Customer;
use App\Models\Kot;
use App\Models\Product;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PosController extends Controller
{
    public function index()
    {
        $menuItems = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $tables = RestaurantTable::query()
            ->where('is_active', true)
            ->orderBy('table_number')
            ->get();

        $taxes = Tax::query()
            ->where('is_active', true)
            ->get();

        $combos = Combo::query()
            ->where('is_active', true)
            ->with(['items.product'])
            ->get();

        return view(
            'admin.pos.index',
            compact(
                'menuItems',
                'tables',
                'taxes',
                'combos'
            )
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'customer_id' => [
                'required',
                Rule::in(array_merge(['walk_in'], Customer::query()->pluck('id')->map(fn ($id) => (string) $id)->all())),
            ],

            'restaurant_table_id' => [
                'required_if:order_type,dine_in',
                'nullable',
                'exists:restaurant_tables,id',
            ],

            'order_type' => [
                'required',
                'in:dine_in,takeaway,delivery',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.type' => [
                'required',
                'string',
                'in:product,combo',
            ],

            'items.*.id' => [
                'required',
                'numeric',
            ],

            'items.*.menu_item_id' => [
                'nullable',
                'exists:products,id',
            ],

            'items.*.combo_id' => [
                'nullable',
                'exists:combos,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);


        $validated['customer_id'] = $validated['customer_id'] === 'walk_in' ? null : $validated['customer_id'];

        return DB::transaction(function () use ($validated) {

            $subtotal = 0;

            $items = [];


            foreach ($validated['items'] as $item) {
                $type = $item['type'] ?? 'product';
                $quantity = (float) $item['quantity'];

                if ($type === 'combo') {
                    $comboId = $item['combo_id'] ?? $item['id'] ?? null;
                    $combo = Combo::query()->where('is_active', true)->findOrFail($comboId);

                    $unitPrice = (float) $combo->price;
                    $total = $unitPrice * $quantity;
                    $subtotal += $total;

                    $items[] = [
                        'menu_item_id' => null,
                        'item_name' => $combo->name . ' (Combo)',
                        'size' => null,
                        'unit' => null,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'discount' => 0,
                        'tax' => 0,
                        'total' => $total,
                    ];

                    continue;
                }

                $productId = $item['menu_item_id'] ?? $item['id'] ?? null;
                $product = Product::query()->where('is_active', true)->findOrFail($productId);

                $unitPrice = (float) $product->price;
                $total = $unitPrice * $quantity;

                $subtotal += $total;

                $items[] = [
                    'menu_item_id' => $product->id,
                    'item_name' => $product->name,
                    'size' => $product->size,
                    'unit' => $product->unit,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'discount' => 0,
                    'tax' => 0,
                    'total' => $total,
                ];
            }


            $discountPercent = (float) (
                $validated['discount'] ?? 0
            );

            $discount = $subtotal > 0
                ? min($subtotal, ($subtotal * $discountPercent) / 100)
                : 0;

            $taxableAmount =
                max(0, $subtotal - $discount);

            $activeTaxes = Tax::query()
                ->where('is_active', true)
                ->get();

            $tax = 0;
            if ($taxableAmount > 0) {
                foreach ($activeTaxes as $taxRule) {
                    if ($taxRule->type === 'percentage') {
                        $tax += $taxableAmount * ($taxRule->rate / 100);
                    } elseif ($taxRule->type === 'fixed') {
                        $tax += (float) $taxRule->rate;
                    }
                }
            }
            $tax = round($tax, 2);

            $grandTotal =
                $taxableAmount + $tax;


            $order = Order::create([

                'order_number' =>
                    $this->generateOrderNumber(),

                'customer_id' =>
                    $validated['customer_id'] ?? null,

                'restaurant_table_id' =>
                    $validated['restaurant_table_id'] ?? null,

                'user_id' =>
                    auth()->id(),

                'order_type' =>
                    $validated['order_type'],

                'status' =>
                    'placed',

                'subtotal' =>
                    $subtotal,

                'discount' =>
                    $discount,

                'tax' =>
                    $tax,

                'grand_total' =>
                    $grandTotal,

                'notes' =>
                    $validated['notes'] ?? null,

            ]);


            foreach ($items as $item) {

                $order->items()->create(
                    $item
                );

            }

            $order->load('items');

            $kot = Kot::create([
                'kot_number' => $this->generateKotNumber(),
                'order_id' => $order->id,
                'restaurant_table_id' => $order->restaurant_table_id,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'sent_at' => now(),
            ]);

            foreach ($order->items as $orderItem) {
                $kot->items()->create([
                    'order_item_id' => $orderItem->id,
                    'item_name' => $orderItem->item_name,
                    'size' => $orderItem->size,
                    'unit' => $orderItem->unit,
                    'quantity' => $orderItem->quantity,
                    'notes' => null,
                    'status' => 'pending',
                ]);

                if ($orderItem->item_name && str_contains($orderItem->item_name, ' (Combo)')) {
                    $comboName = str_replace(' (Combo)', '', $orderItem->item_name);
                    $combo = Combo::query()->where('name', $comboName)->with('items.product')->first();

                    if ($combo) {
                        foreach ($combo->items as $comboItem) {
                            $product = $comboItem->product;

                            if (!$product) {
                                continue;
                            }

                            $kot->items()->create([
                                'order_item_id' => $orderItem->id,
                                'item_name' => $product->name,
                                'size' => $product->size,
                                'unit' => $product->unit,
                                'quantity' => (float) $orderItem->quantity * (float) $comboItem->quantity,
                                'notes' => 'Combo: ' . $combo->name,
                                'status' => 'pending',
                            ]);
                        }
                    }
                }
            }

            $order->update([
                'status' => 'preparing',
            ]);

            /*
             * Mark table occupied for dine-in.
             */

            if (
                $validated['order_type'] === 'dine_in'
                &&
                !empty(
                    $validated['restaurant_table_id']
                )
            ) {

                RestaurantTable::where(
                    'id',
                    $validated['restaurant_table_id']
                )->update([
                    'status' => 'occupied',
                ]);

            }


            return redirect()
                ->route(
                    'admin.pos.index',
                    $order
                )
                ->with(
                    'success',
                    'Order '
                    . $order->order_number
                    . ' created successfully and invoice generated.'
                );
        });
    }


    private function generateOrderNumber(): string
    {
        do {

            $number =
                'ORD-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(
                    substr(
                        uniqid(),
                        -5
                    )
                );

        } while (
            Order::where(
                'order_number',
                $number
            )->exists()
        );


        return $number;
    }

    private function generateKotNumber(): string
    {
        do {
            $number = 'KOT-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(substr(uniqid(), -5));
        } while (Kot::where('kot_number', $number)->exists());

        return $number;
    }
}