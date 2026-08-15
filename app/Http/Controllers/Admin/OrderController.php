<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with([
            'customer',
            'table',
            'items',
            'kots'
        ]);

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

        $orders = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }


    public function show(Order $order)
    {
        $order->load([
            'customer',
            'table',
            'items.product',
            'kots.items',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }


    public function updateStatus(
        Request $request,
        Order $order
    ) {

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
}