<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()
            ->withCount('reservations');

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

            });
        }

        if ($request->filled('status')) {

            $query->where(
                'is_active',
                $request->status === 'active'
            );
        }

        $customers = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.customers.index',
            compact('customers')
        );
    }


    public function create()
    {
        return view(
            'admin.customers.create'
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
                'unique:customers,phone',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ]);

        $validated['is_active'] =
            $request->boolean('is_active', true);

        Customer::create($validated);

        return redirect()
            ->route('admin.customers.index')
            ->with(
                'success',
                'Customer created successfully.'
            );
    }


    public function show(Customer $customer)
    {
        $customer->load([
            'reservations' => function ($query) {

                $query
                    ->with('table')
                    ->latest();

            }
        ]);

        return view(
            'admin.customers.show',
            compact('customer')
        );
    }


    public function edit(Customer $customer)
    {
        return view(
            'admin.customers.edit',
            compact('customer')
        );
    }


    public function update(
        Request $request,
        Customer $customer
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique(
                    'customers',
                    'phone'
                )->ignore($customer->id),
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        $customer->update($validated);

        return redirect()
            ->route(
                'admin.customers.index'
            )
            ->with(
                'success',
                'Customer updated successfully.'
            );
    }


    public function destroy(Customer $customer)
    {
        /*
         * Don't delete customers having reservations.
         */

        if ($customer->reservations()->exists()) {

            return back()->with(
                'error',
                'This customer cannot be deleted because reservation history exists.'
            );
        }

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with(
                'success',
                'Customer deleted successfully.'
            );
    }


    public function search(Request $request)
    {
        $search = trim(
            $request->get('search', '')
        );

        if (strlen($search) < 2) {

            return response()->json([]);

        }

        $customers = Customer::query()
            ->where('is_active', true)
            ->where(function ($query) use ($search) {

                $query->where(
                    'name',
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

            })
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