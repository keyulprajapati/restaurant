<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Combo;
use App\Models\Customer;
use App\Models\Kot;
use App\Models\Order;
use App\Models\Product;
use App\Models\RestaurantTable;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableOrderController extends Controller
{
    /**
     * Show digital menu for a specific table.
     */
    public function showMenu($identifier)
    {
        $table = RestaurantTable::where('table_number', $identifier)
            ->orWhere('id', $identifier)
            ->first();

        if (!$table || !$table->is_active) {
            return view('frontend.table_not_found', [
                'identifier' => $identifier
            ]);
        }

        // Active products
        $products = Product::where('is_active', true)
            ->orderBy('food_type')
            ->orderBy('name')
            ->get();

        // Active combos
        $combos = Combo::where('is_active', true)
            ->with(['items.product'])
            ->get();

        // Active taxes
        $taxes = Tax::where('is_active', true)->get();

        // Categories if any
        $categories = Category::all();

        // Existing active order on this table if any
        $activeOrder = Order::with('items')
            ->where('restaurant_table_id', $table->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->first();

        return view('frontend.table_menu', compact(
            'table',
            'products',
            'combos',
            'taxes',
            'categories',
            'activeOrder'
        ));
    }

    /**
     * Place an order from the table frontend.
     */
    public function placeOrder(Request $request, $identifier)
    {
        $table = RestaurantTable::where('table_number', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required'],
            'items.*.type' => ['nullable', 'string', 'in:product,combo'],
            'items.*.quantity' => ['required', 'numeric', 'min:1', 'max:50'],
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order = DB::transaction(function () use ($validated, $table, $request) {
            // Find or create customer if phone/name provided
            $customerId = null;
            if (!empty($validated['customer_phone']) || !empty($validated['customer_name'])) {
                $phone = trim($validated['customer_phone'] ?? '');
                $name = trim($validated['customer_name'] ?? 'Table Guest');

                if ($phone) {
                    $customer = Customer::firstOrCreate(
                        ['phone' => $phone],
                        ['name' => $name, 'is_active' => true]
                    );
                    if ($name && $customer->name !== $name) {
                        $customer->update(['name' => $name]);
                    }
                    $customerId = $customer->id;
                } elseif ($name) {
                    $customer = Customer::create([
                        'name' => $name,
                        'is_active' => true,
                    ]);
                    $customerId = $customer->id;
                }
            }

            // Calculate subtotal from trusted DB prices
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $itemInput) {
                $isCombo = ($itemInput['type'] ?? '') === 'combo';
                $qty = (float) $itemInput['quantity'];

                if ($isCombo) {
                    $combo = Combo::where('is_active', true)->findOrFail($itemInput['id']);
                    $price = (float) $combo->price;
                    $lineTotal = $price * $qty;
                    $subtotal += $lineTotal;

                    $itemsData[] = [
                        'menu_item_id' => null,
                        'item_name' => $combo->name . ' (Combo)',
                        'size' => null,
                        'unit_price' => $price,
                        'quantity' => $qty,
                        'discount' => 0,
                        'tax' => 0,
                        'total' => $lineTotal,
                        'notes' => null,
                    ];
                } else {
                    $product = Product::where('is_active', true)->findOrFail($itemInput['id']);
                    $price = (float) $product->price;
                    $lineTotal = $price * $qty;
                    $subtotal += $lineTotal;

                    $itemsData[] = [
                        'menu_item_id' => $product->id,
                        'item_name' => $product->name,
                        'size' => $product->size ? $product->formatted_size : null,
                        'unit' => $product->unit,
                        'unit_price' => $price,
                        'quantity' => $qty,
                        'discount' => 0,
                        'tax' => 0,
                        'total' => $lineTotal,
                        'notes' => null,
                    ];
                }
            }

            // Calculate active taxes
            $activeTaxes = Tax::where('is_active', true)->get();
            $taxAmount = 0;
            foreach ($activeTaxes as $taxRule) {
                if ($taxRule->type === 'percentage') {
                    $taxAmount += $subtotal * ((float) $taxRule->rate / 100);
                } elseif ($taxRule->type === 'fixed') {
                    $taxAmount += (float) $taxRule->rate;
                }
            }
            $taxAmount = round($taxAmount, 2);
            $grandTotal = round($subtotal + $taxAmount, 2);

            // Generate order number
            $orderNumber = $this->generateOrderNumber();

            // Notes with table info
            $notes = $validated['notes'] ?? null;

            // Create Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customerId,
                'restaurant_table_id' => $table->id,
                'user_id' => null,
                'order_type' => 'dine_in',
                'status' => 'placed',
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => $taxAmount,
                'grand_total' => $grandTotal,
                'notes' => $notes,
            ]);

            // Create Order Items
            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }

            $order->load('items');

            $kot = Kot::create([
                'kot_number' => $this->generateKotNumber(),
                'order_id' => $order->id,
                'restaurant_table_id' => $order->restaurant_table_id,
                'status' => 'pending',
                'notes' => $notes,
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

            $order->update(['status' => 'preparing']);

            // Mark table occupied
            $table->update(['status' => 'occupied']);

            return $order;
        });

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'redirect' => route('table.order.track', $order->order_number),
            ]);
        }

        return redirect()->route('table.order.track', $order->order_number)
            ->with('success', 'Order placed successfully! Table #' . $table->table_number);
    }

    /**
     * Customer live order tracking screen.
     */
    public function trackOrder($orderNumber)
    {
        $order = Order::with(['table', 'items', 'customer'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('frontend.order_track', compact('order'));
    }

    /**
     * API endpoint to poll real-time status of the order.
     */
    public function orderStatusApi($orderNumber)
    {
        $order = Order::with('table')
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $statusSteps = [
            'placed' => ['step' => 1, 'label' => 'Order Placed', 'badge' => 'primary', 'desc' => 'Your order has been received by the restaurant.'],
            'preparing' => ['step' => 2, 'label' => 'Preparing Food', 'badge' => 'warning', 'desc' => 'The kitchen chef is actively preparing your dishes.'],
            'ready' => ['step' => 3, 'label' => 'Ready to Serve', 'badge' => 'info', 'desc' => 'Your food is ready and will be brought to your table shortly.'],
            'served' => ['step' => 4, 'label' => 'Served on Table', 'badge' => 'success', 'desc' => 'Enjoy your meal!'],
            'completed' => ['step' => 5, 'label' => 'Completed', 'badge' => 'success', 'desc' => 'Order completed. Thank you for dining with us!'],
            'cancelled' => ['step' => 0, 'label' => 'Cancelled', 'badge' => 'danger', 'desc' => 'This order was cancelled.'],
        ];

        $currentStepInfo = $statusSteps[$order->status] ?? [
            'step' => 1,
            'label' => ucfirst($order->status),
            'badge' => 'secondary',
            'desc' => 'Processing order',
        ];

        return response()->json([
            'order_number' => $order->order_number,
            'table_number' => $order->table?->table_number ?? 'Dine-In',
            'table_id' => $order->restaurant_table_id,
            'status' => $order->status,
            'step' => $currentStepInfo['step'],
            'status_label' => $currentStepInfo['label'],
            'status_badge' => $currentStepInfo['badge'],
            'status_desc' => $currentStepInfo['desc'],
            'grand_total' => (float) $order->grand_total,
            'is_finished' => in_array($order->status, ['completed', 'cancelled']),
        ]);
    }

    /**
     * Generate unique order number.
     */
    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    private function generateKotNumber(): string
    {
        do {
            $number = 'KOT-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        } while (Kot::where('kot_number', $number)->exists());

        return $number;
    }
}
