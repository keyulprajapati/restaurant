<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kot;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KotController extends Controller
{
    public function index(Request $request)
    {
        $query = Kot::with([
            'order.customer',
            'table',
            'items',
        ]);


        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        $kots = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();


        return view(
            'admin.kots.index',
            compact('kots')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create KOT from Order
    |--------------------------------------------------------------------------
    */

    public function create(Order $order)
    {
        $order->load([
            'items',
            'customer',
            'table',
        ]);


        return view(
            'admin.kots.create',
            compact('order')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store KOT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'order_id' => [
                'required',
                'exists:orders,id',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.order_item_id' => [
                'required',
                'exists:order_items,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'items.*.notes' => [
                'nullable',
                'string',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);


        $order = Order::with([
            'items',
            'table'
        ])->findOrFail(
            $validated['order_id']
        );


        $kot = DB::transaction(
            function () use (
                $validated,
                $order
            ) {

                $kot = Kot::create([

                    'kot_number' =>
                        $this->generateKotNumber(),

                    'order_id' =>
                        $order->id,

                    'restaurant_table_id' =>
                        $order->restaurant_table_id,

                    'status' =>
                        'pending',

                    'notes' =>
                        $validated['notes'] ?? null,

                    'sent_at' =>
                        now(),

                ]);


                foreach (
                    $validated['items']
                    as $item
                ) {

                    $orderItem =
                        $order->items
                            ->firstWhere(
                                'id',
                                $item['order_item_id']
                            );


                    if (!$orderItem) {
                        continue;
                    }


                    $kot->items()->create([

                        'order_item_id' =>
                            $orderItem->id,

                        'item_name' =>
                            $orderItem->item_name,

                        'size' =>
                            $orderItem->size,

                        'quantity' =>
                            $item['quantity'],

                        'notes' =>
                            $item['notes'] ?? null,

                        'status' =>
                            'pending',

                    ]);
                }


                /*
                 * Update order status.
                 */

                $order->update([
                    'status' => 'preparing',
                ]);


                return $kot;
            }
        );


        return redirect()
            ->route(
                'admin.kots.show',
                $kot
            )
            ->with(
                'success',
                'KOT created successfully.'
            );
    }


    public function show(Kot $kot)
    {
        $kot->load([
            'order.customer',
            'order.table',
            'items',
        ]);


        return view(
            'admin.kots.show',
            compact('kot')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update KOT Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        Kot $kot
    ) {

        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,preparing,ready,served,cancelled',
            ],
        ]);


        $data = [
            'status' =>
                $validated['status'],
        ];


        switch ($validated['status']) {

            case 'preparing':

                $data['prepared_at'] =
                    now();

                break;


            case 'ready':

                $data['ready_at'] =
                    now();

                break;


            case 'served':

                $data['served_at'] =
                    now();

                break;

        }


        DB::transaction(
            function () use (
                $kot,
                $data
            ) {

                $kot->update($data);


                /*
                 * Update every KOT item.
                 */

                $kot->items()->update([
                    'status' =>
                        $data['status'],
                ]);


                /*
                 * Update order according to KOT.
                 */

                if (
                    $data['status'] === 'preparing'
                ) {

                    $kot->order->update([
                        'status' =>
                            'preparing',
                    ]);

                }


                if (
                    $data['status'] === 'ready'
                ) {

                    $kot->order->update([
                        'status' =>
                            'ready',
                    ]);

                }


                if (
                    $data['status'] === 'served'
                ) {

                    $kot->order->update([
                        'status' =>
                            'served',
                    ]);

                }
            }
        );


        return back()->with(
            'success',
            'KOT status updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Print
    |--------------------------------------------------------------------------
    */

    public function print(Kot $kot)
    {
        $kot->load([
            'order.customer',
            'order.table',
            'items',
        ]);


        return view(
            'admin.kots.print',
            compact('kot')
        );
    }


    private function generateKotNumber(): string
    {
        do {

            $number =
                'KOT-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(
                    substr(
                        uniqid(),
                        -5
                    )
                );

        } while (
            Kot::where(
                'kot_number',
                $number
            )->exists()
        );


        return $number;
    }
}