<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Customer;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with([
    'customer',
    'table'
]);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'customer_name',
                    'like',
                    "%{$search}%"
                )

                    ->orWhere(
                        'phone',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    );

            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('reservation_date')) {

            $query->where(
                'reservation_date',
                $request->reservation_date
            );
        }

        $reservations = $query
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.reservations.index',
            compact('reservations')
        );
    }


    public function create()
    {
        $tables = RestaurantTable::active()
            ->orderBy('table_number')
            ->get();

        return view(
            'admin.reservations.create',
            compact('tables')
        );
    }


    public function store(Request $request)
{
    $validated = $request->validate([

        'customer_id' => [
            'nullable',
            'exists:customers,id',
        ],

        'customer_name' => [
            'required_without:customer_id',
            'nullable',
            'string',
            'max:150',
        ],

        'phone' => [
            'required',
            'string',
            'max:30',
        ],

        'email' => [
            'nullable',
            'email',
            'max:150',
        ],

        'reservation_date' => [
            'required',
            'date',
            'after_or_equal:today',
        ],

        'reservation_time' => [
            'required',
            'date_format:H:i',
        ],

        'guests' => [
            'required',
            'integer',
            'min:1',
            'max:100',
        ],

        'duration_minutes' => [
            'required',
            'integer',
            'min:30',
            'max:480',
        ],

        'restaurant_table_id' => [
            'nullable',
            'exists:restaurant_tables,id',
        ],

        'status' => [
            'required',
            Rule::in([
                'pending',
                'confirmed',
            ]),
        ],

        'special_request' => [
            'nullable',
            'string',
        ],

        'notes' => [
            'nullable',
            'string',
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Find or Create Customer
    |--------------------------------------------------------------------------
    */

    if (!empty($validated['customer_id'])) {

        $customer = Customer::findOrFail(
            $validated['customer_id']
        );

        /*
         * Update customer information if required
         */
        $customer->update([
            'name' => $validated['customer_name']
                ?? $customer->name,

            'email' => $validated['email']
                ?? $customer->email,

        ]);

    } else {

        /*
         * Find existing customer using phone
         */

        $customer = Customer::where(
            'phone',
            $validated['phone']
        )->first();


        if ($customer) {

            /*
             * Existing customer
             */

            $customer->update([
                'name' => $validated['customer_name'],
                'email' => $validated['email']
                    ?? $customer->email,
            ]);

        } else {

            /*
             * New customer
             */

            $customer = Customer::create([
                'name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
            ]);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Table Conflict Check
    |--------------------------------------------------------------------------
    */

    if (
        !empty($validated['restaurant_table_id'])
        &&
        $this->tableHasConflict(
            $validated['restaurant_table_id'],
            $validated['reservation_date'],
            $validated['reservation_time'],
            (int) $validated['duration_minutes']
        )
    ) {

        return back()
            ->withInput()
            ->withErrors([
                'restaurant_table_id' =>
                    'This table is already reserved during this time.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Create Reservation
    |--------------------------------------------------------------------------
    */

    $reservation = Reservation::create([

        'customer_id' => $customer->id,

        'restaurant_table_id' =>
            $validated['restaurant_table_id'] ?? null,

        'reservation_date' =>
            $validated['reservation_date'],

        'reservation_time' =>
            $validated['reservation_time'],

        'guests' =>
            (int) $validated['guests'],

        'duration_minutes' =>
            (int) $validated['duration_minutes'],

        'status' =>
            $validated['status'],

        'special_request' =>
            $validated['special_request'] ?? null,

        'notes' =>
            $validated['notes'] ?? null,

    ]);


    return redirect()
        ->route('admin.reservations.index')
        ->with(
            'success',
            'Reservation created successfully.'
        );
}


    public function edit(Reservation $reservation)
    {
        $tables = RestaurantTable::active()
            ->orderBy('table_number')
            ->get();

        return view(
            'admin.reservations.edit',
            compact(
                'reservation',
                'tables'
            )
        );
    }


    public function update(
    Request $request,
    Reservation $reservation
) {
    $validated = $request->validate([

        'customer_id' => [
            'nullable',
            'exists:customers,id',
        ],

        'customer_name' => [
            'required',
            'string',
            'max:150',
        ],

        'phone' => [
            'required',
            'string',
            'max:30',
        ],

        'email' => [
            'nullable',
            'email',
            'max:150',
        ],

        'reservation_date' => [
            'required',
            'date',
        ],

        'reservation_time' => [
            'required',
            'date_format:H:i',
        ],

        'guests' => [
            'required',
            'integer',
            'min:1',
            'max:100',
        ],

        'duration_minutes' => [
            'required',
            'integer',
            'min:30',
            'max:480',
        ],

        'restaurant_table_id' => [
            'nullable',
            'exists:restaurant_tables,id',
        ],

        'status' => [
            'required',
            Rule::in([
                'pending',
                'confirmed',
                'seated',
                'completed',
                'cancelled',
                'no_show',
            ]),
        ],

        'special_request' => [
            'nullable',
            'string',
        ],

        'notes' => [
            'nullable',
            'string',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Find / Create Customer
    |--------------------------------------------------------------------------
    */

    if (!empty($validated['customer_id'])) {

        $customer = Customer::findOrFail(
            $validated['customer_id']
        );

    } else {

        $customer = Customer::where(
            'phone',
            $validated['phone']
        )->first();

    }


    /*
    |--------------------------------------------------------------------------
    | Create Customer If Not Found
    |--------------------------------------------------------------------------
    */

    if (!$customer) {

        $customer = Customer::create([
            'name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
        ]);

    } else {

        /*
         * Update existing customer
         */

        $customer->update([
            'name' => $validated['customer_name'],
            'email' => $validated['email'] ?? $customer->email,
        ]);

    }


    /*
    |--------------------------------------------------------------------------
    | Cast Numeric Values
    |--------------------------------------------------------------------------
    */

    $duration = (int) $validated['duration_minutes'];

    $guests = (int) $validated['guests'];


    /*
    |--------------------------------------------------------------------------
    | Check Table Conflict
    |--------------------------------------------------------------------------
    */

    if (
        !empty($validated['restaurant_table_id'])
        &&
        $this->tableHasConflict(
            $validated['restaurant_table_id'],
            $validated['reservation_date'],
            $validated['reservation_time'],
            $duration,
            $reservation->id
        )
    ) {

        return back()
            ->withInput()
            ->withErrors([
                'restaurant_table_id' =>
                    'This table is already reserved during this time.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Reservation
    |--------------------------------------------------------------------------
    */

    $reservation->update([

        'customer_id' => $customer->id,

        'restaurant_table_id' =>
            $validated['restaurant_table_id'] ?? null,

        'reservation_date' =>
            $validated['reservation_date'],

        'reservation_time' =>
            $validated['reservation_time'],

        'guests' => $guests,

        'duration_minutes' => $duration,

        'status' =>
            $validated['status'],

        'special_request' =>
            $validated['special_request'] ?? null,

        'notes' =>
            $validated['notes'] ?? null,

    ]);


    return redirect()
        ->route('admin.reservations.index')
        ->with(
            'success',
            'Reservation updated successfully.'
        );
}


    public function destroy(
        Reservation $reservation
    ) {

        $reservation->delete();

        return redirect()
            ->route('admin.reservations.index')
            ->with(
                'success',
                'Reservation deleted successfully.'
            );
    }


    private function tableHasConflict(
        $tableId,
        $date,
        $time,
        $duration,
        $ignoreId = null
    ): bool {

        $start = Carbon::parse(
            $date . ' ' . $time
        );

        $end = $start->copy()
            ->addMinutes((int) $duration);


        $reservations = Reservation::where(
            'restaurant_table_id',
            $tableId
        )
            ->whereDate(
                'reservation_date',
                $date
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'confirmed',
                    'seated',
                ]
            );


        if ($ignoreId) {

            $reservations->where(
                'id',
                '!=',
                $ignoreId
            );
        }


        foreach ($reservations->get() as $reservation) {

            $existingStart = Carbon::parse(
                $reservation->reservation_date
                . ' '
                . $reservation->reservation_time
            );

            $existingEnd = $existingStart->copy()
                ->addMinutes(
                    (int) $reservation->duration_minutes
                );


            if (
                $start < $existingEnd
                &&
                $end > $existingStart
            ) {

                return true;
            }
        }


        return false;
    }

    public function searchCustomer(Request $request)
{
    $search = trim(
        $request->get('search')
    );

    if (!$search) {
        return response()->json([]);
    }

    $customers = Customer::query()
        ->where('phone', 'like', "%{$search}%")
        ->orWhere('name', 'like', "%{$search}%")
        ->limit(10)
        ->get([
            'id',
            'name',
            'phone',
            'email',
        ]);

    return response()->json(
        $customers
    );
}
}