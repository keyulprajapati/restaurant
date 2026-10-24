<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Kot;
use App\Models\Order;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedBranchIds = $user->allowedBranchIds();
        $branches = Branch::query()
            ->where('is_active', true)
            ->when($allowedBranchIds !== null, fn ($query) => $query->whereIn('id', $allowedBranchIds))
            ->orderBy('name')
            ->get();

        $query = Order::with([
            'customer',
            'table',
            'items',
            'kots',
            'branch',
        ])->when($allowedBranchIds !== null, fn ($query) => $query->whereIn('branch_id', $allowedBranchIds));

        if ($request->filled('branch_id')) {
            $this->ensureBranchAccess((int) $request->branch_id);
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('order_type')) {

            $query->where(
                'order_type',
                $request->order_type
            );
        }

        if ($request->filled('restaurant_table_id')) {
            $table = RestaurantTable::findOrFail($request->restaurant_table_id);
            $this->ensureBranchAccess((int) $table->branch_id);
            $query->where(
                'restaurant_table_id',
                $request->restaurant_table_id
            );
        }

        $orders = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $tables = RestaurantTable::query()
            ->when($allowedBranchIds !== null, fn ($query) => $query->whereIn('branch_id', $allowedBranchIds))
            ->orderBy('table_number')
            ->get();

        return view(
            'admin.orders.index',
            compact('orders', 'tables', 'branches')
        );
    }


    public function show(Order $order)
    {
        $this->ensureOrderAccess($order);
        $order->load([
            'customer',
            'table',
            'items.product',
            'kots.items',
            'branch',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    public function receipt(Order $order)
    {
        $this->ensureOrderAccess($order);

        DB::transaction(function () use ($order) {
            $order->update([
                'status' => 'completed',
            ]);

            $order->kots()
                ->where('status', '!=', 'cancelled')
                ->get()
                ->each(function (Kot $kot) {
                    $kot->update([
                        'status' => 'served',
                        'served_at' => $kot->served_at ?? now(),
                    ]);
                    $kot->items()->update([
                        'status' => 'served',
                    ]);
                });

            if ($order->restaurant_table_id) {
                RestaurantTable::where(
                    'id',
                    $order->restaurant_table_id
                )->update([
                    'status' => 'available',
                ]);
            }
        });

        $order->load([
            'customer',
            'table',
            'items',
            'branch',
        ]);

        return view(
            'admin.orders.receipt',
            compact('order')
        );
    }


    public function updateStatus(
        Request $request,
        Order $order
    ) {

        $this->ensureOrderAccess($order);

        $request->validate([
            'status' => [
                'required',
                'in:draft,placed,preparing,ready,served,completed,cancelled',
            ],
        ]);


        DB::transaction(function () use (
            $request,
            $order
        ) {

            $order->update([
                'status' => $request->status,
            ]);


            /*
             * Release table when order is completed
             * or cancelled.
             */

            if (
                in_array(
                    $request->status,
                    [
                        'completed',
                        'cancelled'
                    ]
                )
                &&
                $order->restaurant_table_id
            ) {

                RestaurantTable::where(
                    'id',
                    $order->restaurant_table_id
                )->update([
                    'status' => 'available',
                ]);
            }
        });


        return back()->with(
            'success',
            'Order status updated successfully.'
        );
    }

    public function updatePaymentMethod(Request $request, Order $order)
    {
        $this->ensureOrderAccess($order);

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:cash,upi,card,other',
            ],
        ]);

        $order->update([
            'payment_method' => $validated['payment_method'],
        ]);

        return back()->with(
            'success',
            'Payment method updated successfully.'
        );
    }

    private function ensureOrderAccess(Order $order): void
    {
        $user = auth()->user();
        $hasAccess = $order->branch_id
            ? $user->canAccessBranch((int) $order->branch_id)
            : $user->all_branches;

        abort_unless($hasAccess, 403);
    }

    private function ensureBranchAccess(int $branchId): void
    {
        abort_unless(auth()->user()->canAccessBranch($branchId), 403);
    }
}