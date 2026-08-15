<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view(
            'admin.pos.index',
            compact(
                'menuItems',
                'tables'
            )
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],

            'restaurant_table_id' => [
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

            'items.*.menu_item_id' => [
                'nullable',
                'exists:products,id',
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
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);


        return DB::transaction(function () use ($validated) {

            $subtotal = 0;

            $items = [];


            foreach ($validated['items'] as $item) {

                $product = Product::findOrFail(
                    $item['menu_item_id']
                );

                $quantity = (float) $item['quantity'];

                $unitPrice = (float) $product->price;

                $total = $unitPrice * $quantity;

                $subtotal += $total;

                $items[] = [
                    'menu_item_id' => $product->id,
                    'item_name' => $product->name,
                    'size' => $product->size,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'discount' => 0,
                    'tax' => 0,
                    'total' => $total,
                ];
            }


            $discount = (float) (
                $validated['discount'] ?? 0
            );


            $taxableAmount =
                max(0, $subtotal - $discount);


            /*
             * Replace this with your Tax Management
             * calculation later.
             */

            $tax = 0;


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
                    'admin.pos.index'
                )
                ->with(
                    'success',
                    'Order '
                    . $order->order_number
                    . ' created successfully.'
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
}