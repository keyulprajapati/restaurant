<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Combo;
use App\Models\Customer;
use App\Models\Kot;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $allowedBranchIds = $currentUser->allowedBranchIds();
        $branches = Branch::query()
            ->where('is_active', true)
            ->when($allowedBranchIds !== null, fn ($query) => $query->whereIn('id', $allowedBranchIds))
            ->orderBy('name')
            ->get();

        $selectedBranchId = $request->query('branch_id', $branches->first()?->id);
        if ($selectedBranchId && !$currentUser->canAccessBranch((int) $selectedBranchId)) {
            abort(403);
        }

        if (empty($selectedBranchId) && $branches->isNotEmpty()) {
            $selectedBranchId = $branches->first()->id;
        }
        $selectedOrderType = $request->query('order_type', 'dine_in');

        $menuItems = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Product $product) => $product->category?->name ?? 'Uncategorized')
            ->sortKeys();

        $tables = RestaurantTable::query()
            ->when($selectedBranchId, fn ($query) => $query->where('branch_id', $selectedBranchId))
            ->where('is_active', true)
            ->orderBy('table_number')
            ->get();

        $selectedTableId = $request->query('restaurant_table_id');
        $openOrder = null;
        $existingOrderItems = [];
        $existingOrderData = null;

        if ($request->query('order_type') === 'dine_in' && $selectedTableId) {
            $openOrder = Order::query()
                ->with(['items', 'customer'])
                ->where('branch_id', $selectedBranchId)
                ->where('restaurant_table_id', $selectedTableId)
                ->where('order_type', 'dine_in')
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->latest()
                ->first();

            if ($openOrder) {
                $existingOrderItems = $openOrder->items->map(function (OrderItem $item) {
                    $isCombo = str_ends_with($item->item_name, ' (Combo)');
                    $comboName = $isCombo
                        ? substr($item->item_name, 0, -strlen(' (Combo)'))
                        : null;

                    return [
                        'order_item_id' => $item->id,
                        'id' => $isCombo
                            ? Combo::where('name', $comboName)->value('id')
                            : $item->menu_item_id,
                        'type' => $isCombo ? 'combo' : 'product',
                        'name' => $isCombo ? $comboName : $item->item_name,
                        'size' => $item->size,
                        'unit' => $item->unit,
                        'price' => (float) $item->unit_price,
                        'quantity' => (float) $item->quantity,
                    ];
                })->values()->all();

                if (collect($existingOrderItems)->contains(fn (array $item) => $item['id'] === null)) {
                    abort(409, 'This order contains an item that is no longer available in the menu.');
                }

                $existingOrderData = [
                    'customer_id' => $openOrder->customer_id ?? 'walk_in',
                    'notes' => $openOrder->notes,
                    'discount_percent' => (float) $openOrder->subtotal > 0
                        ? min(100, ((float) $openOrder->discount / (float) $openOrder->subtotal) * 100)
                        : 0,
                ];
            }
        }

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
                'combos',
                'branches',
                'selectedBranchId',
                'openOrder',
                'existingOrderItems',
                'existingOrderData',
                'selectedTableId',
                'selectedOrderType',
            )
        );
    }


    public function store(Request $request)
    {
        $currentUser = auth()->user();

        $validated = $request->validate([

            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')->where('is_active', true),
                function ($attribute, $value, $fail) use ($currentUser) {
                    if (!$currentUser->canAccessBranch((int) $value)) {
                        $fail('You do not have access to the selected branch.');
                    }
                },
            ],

            'customer_id' => [
                'required',
                Rule::in(array_merge(['walk_in'], Customer::query()->pluck('id')->map(fn ($id) => (string) $id)->all())),
            ],

            'restaurant_table_id' => [
                'required_if:order_type,dine_in',
                'nullable',
                Rule::exists('restaurant_tables', 'id')->where('branch_id', $request->input('branch_id')),
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

            'items.*.order_item_id' => [
                'nullable',
                'integer',
                'distinct',
            ],

            'existing_order_id' => [
                'nullable',
                'integer',
                'exists:orders,id',
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
            if (!empty($validated['restaurant_table_id'])) {
                RestaurantTable::whereKey($validated['restaurant_table_id'])
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            $openOrderQuery = Order::query()
                ->where('branch_id', $validated['branch_id'])
                ->where('restaurant_table_id', $validated['restaurant_table_id'] ?? null)
                ->where('order_type', 'dine_in')
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->latest();

            if (!empty($validated['existing_order_id'])) {
                $openOrderQuery->whereKey($validated['existing_order_id']);
            }

            $order = !empty($validated['restaurant_table_id'])
                && $validated['order_type'] === 'dine_in'
                    ? $openOrderQuery->lockForUpdate()->first()
                    : null;

            if (!empty($validated['existing_order_id']) && !$order) {
                abort(409, 'This order is no longer open. Refresh POS and try again.');
            }

            if ($order && empty($validated['existing_order_id'])) {
                abort(409, 'An open order already exists for this table. Refresh POS to load it before making changes.');
            }

            $isExistingOrder = $order !== null;
            $existingItems = $order
                ? $order->items()->get()->keyBy('id')
                : collect();
            $submittedItemIds = [];
            $kotItemsToSend = [];

            $subtotal = 0;

            $items = [];


            foreach ($validated['items'] as $item) {
                $type = $item['type'] ?? 'product';
                $quantity = (float) $item['quantity'];

                if ($type === 'combo') {
                    $comboId = $item['combo_id'] ?? $item['id'] ?? null;
                    $orderItemId = $item['order_item_id'] ?? null;
                    $existingOrderItem = $orderItemId
                        ? $existingItems->get($orderItemId)
                        : null;
                    if ($orderItemId && !$existingOrderItem) {
                        abort(422, 'An order item does not belong to the selected open order.');
                    }

                    $comboQuery = Combo::query()->whereKey($comboId);
                    if (!$existingOrderItem) {
                        $comboQuery->where('is_active', true);
                    }
                    $combo = $comboQuery->firstOrFail();

                    $itemName = $combo->name . ' (Combo)';
                    $unitPrice = $existingOrderItem && $existingOrderItem->item_name === $itemName
                        ? (float) $existingOrderItem->unit_price
                        : (float) $combo->price;
                    $total = $unitPrice * $quantity;
                    $subtotal += $total;

                    $items[] = [
                        'menu_item_id' => null,
                        'item_name' => $itemName,
                        'size' => null,
                        'unit' => null,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'discount' => 0,
                        'tax' => 0,
                        'total' => $total,
                    ];

                    $this->saveOrderItem(
                        $order,
                        $existingItems,
                        $submittedItemIds,
                        $kotItemsToSend,
                        $orderItemId,
                        end($items),
                        $quantity
                    );

                    continue;
                }

                $productId = $item['menu_item_id'] ?? $item['id'] ?? null;
                $orderItemId = $item['order_item_id'] ?? null;
                $existingOrderItem = $orderItemId
                    ? $existingItems->get($orderItemId)
                    : null;
                if ($orderItemId && !$existingOrderItem) {
                    abort(422, 'An order item does not belong to the selected open order.');
                }

                $productQuery = Product::query()->whereKey($productId);
                if (!$existingOrderItem) {
                    $productQuery->where('is_active', true);
                }
                $product = $productQuery->firstOrFail();

                $unitPrice = $existingOrderItem
                    && $existingOrderItem->menu_item_id === $product->id
                    && $existingOrderItem->item_name === $product->name
                        ? (float) $existingOrderItem->unit_price
                        : (float) $product->price;
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

                $this->saveOrderItem(
                    $order,
                    $existingItems,
                    $submittedItemIds,
                    $kotItemsToSend,
                    $orderItemId,
                    end($items),
                    $quantity
                );
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


            if ($order) {
                $order->update([
                    'customer_id' => $validated['customer_id'] ?? null,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                    'notes' => $validated['notes'] ?? null,
                ]);

                if ($kotItemsToSend) {
                    $order->update(['status' => 'preparing']);
                }

                $existingItems
                    ->except($submittedItemIds)
                    ->each
                    ->delete();
            } else {
                $order = Order::create([
                    'branch_id' => $validated['branch_id'],
                    'order_number' => $this->generateOrderNumber(),
                    'customer_id' => $validated['customer_id'] ?? null,
                    'restaurant_table_id' => $validated['restaurant_table_id'] ?? null,
                    'user_id' => auth()->id(),
                    'order_type' => $validated['order_type'],
                    'status' => 'placed',
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    $orderItem = $order->items()->create($item);
                    $kotItemsToSend[] = [
                        'order_item_id' => $orderItem->id,
                        'item_name' => $orderItem->item_name,
                        'size' => $orderItem->size,
                        'unit' => $orderItem->unit,
                        'quantity' => $orderItem->quantity,
                    ];
                }

                $order->update(['status' => 'preparing']);
            }

            if ($kotItemsToSend) {
                $this->createKot($order, $kotItemsToSend, $validated['notes'] ?? null);
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
                ->route('admin.pos.index', [
                    'branch_id' => $order->branch_id,
                    'order_type' => $order->order_type,
                    'restaurant_table_id' => $order->restaurant_table_id,
                ])
                ->with(
                    'success',
                    'Order '
                    . $order->order_number
                    . ($isExistingOrder ? ' updated successfully.' : ' created successfully.')
                );
        });
    }

    private function saveOrderItem(
        ?Order $order,
        $existingItems,
        array &$submittedItemIds,
        array &$kotItemsToSend,
        ?int $orderItemId,
        array $itemData,
        float $quantity
    ): void {
        if (!$order) {
            return;
        }

        $existingItem = $orderItemId ? $existingItems->get($orderItemId) : null;
        if ($orderItemId && !$existingItem) {
            abort(422, 'An order item does not belong to the selected open order.');
        }

        if ($existingItem) {
            $submittedItemIds[] = $existingItem->id;
            $sameItem = $existingItem->item_name === $itemData['item_name']
                && (string) $existingItem->menu_item_id === (string) $itemData['menu_item_id'];
            $previousQuantity = (float) $existingItem->quantity;
            $existingItem->update($itemData);
            $orderItem = $existingItem;

            $quantityToSend = $sameItem
                ? max(0, $quantity - $previousQuantity)
                : $quantity;
        } else {
            $orderItem = $order->items()->create($itemData);
            $submittedItemIds[] = $orderItem->id;
            $quantityToSend = $quantity;
        }

        if ($quantityToSend > 0) {
            $kotItemsToSend[] = [
                'order_item_id' => $orderItem->id,
                'item_name' => $orderItem->item_name,
                'size' => $orderItem->size,
                'unit' => $orderItem->unit,
                'quantity' => $quantityToSend,
            ];
        }
    }

    private function createKot(Order $order, array $items, ?string $notes): void
    {
        $kot = Kot::create([
            'kot_number' => $this->generateKotNumber(),
            'order_id' => $order->id,
            'restaurant_table_id' => $order->restaurant_table_id,
            'status' => 'pending',
            'notes' => $notes,
            'sent_at' => now(),
        ]);

        foreach ($items as $item) {
            $kot->items()->create([
                'order_item_id' => $item['order_item_id'],
                'item_name' => $item['item_name'],
                'size' => $item['size'],
                'unit' => $item['unit'],
                'quantity' => $item['quantity'],
                'notes' => null,
                'status' => 'pending',
            ]);

            if (!str_ends_with($item['item_name'], ' (Combo)')) {
                continue;
            }

            $comboName = substr($item['item_name'], 0, -strlen(' (Combo)'));
            $combo = Combo::query()
                ->where('name', $comboName)
                ->with('items.product')
                ->first();

            if (!$combo) {
                continue;
            }

            foreach ($combo->items as $comboItem) {
                $product = $comboItem->product;

                if (!$product) {
                    continue;
                }

                $kot->items()->create([
                    'order_item_id' => $item['order_item_id'],
                    'item_name' => $product->name,
                    'size' => $product->size,
                    'unit' => $product->unit,
                    'quantity' => (float) $item['quantity'] * (float) $comboItem->quantity,
                    'notes' => 'Combo: ' . $combo->name,
                    'status' => 'pending',
                ]);
            }
        }
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