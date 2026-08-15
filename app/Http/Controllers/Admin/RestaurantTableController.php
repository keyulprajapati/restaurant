<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestaurantTableController extends Controller
{
    public function index(Request $request)
    {
        $query = RestaurantTable::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'table_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'area',
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

        if ($request->filled('area')) {
            $query->where(
                'area',
                $request->area
            );
        }

        $tables = $query
            ->orderBy('table_number')
            ->paginate(15)
            ->withQueryString();

        $areas = RestaurantTable::query()
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->distinct()
            ->orderBy('area')
            ->pluck('area');

        return view(
            'admin.tables.index',
            compact(
                'tables',
                'areas'
            )
        );
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                'unique:restaurant_tables,table_number',
            ],

            'name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'area' => [
                'nullable',
                'string',
                'max:100',
            ],

            'table_type' => [
                'required',
                Rule::in([
                    'regular',
                    'round',
                    'square',
                    'outdoor',
                    'private',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        RestaurantTable::create([
            'table_number' => $validated['table_number'],
            'name' => $validated['name'] ?? null,
            'capacity' => $validated['capacity'],
            'area' => $validated['area'] ?? null,
            'table_type' => $validated['table_type'],
            'status' => 'available',
            'is_active' => $request->boolean('is_active'),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.tables.index')
            ->with(
                'success',
                'Restaurant table created successfully.'
            );
    }

    public function edit(RestaurantTable $table)
    {
        return view(
            'admin.tables.edit',
            compact('table')
        );
    }

    public function update(
        Request $request,
        RestaurantTable $table
    ) {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'restaurant_tables',
                    'table_number'
                )->ignore($table->id),
            ],

            'name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'area' => [
                'nullable',
                'string',
                'max:100',
            ],

            'table_type' => [
                'required',
                Rule::in([
                    'regular',
                    'round',
                    'square',
                    'outdoor',
                    'private',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'occupied',
                    'reserved',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $table->update([
            'table_number' => $validated['table_number'],
            'name' => $validated['name'] ?? null,
            'capacity' => $validated['capacity'],
            'area' => $validated['area'] ?? null,
            'table_type' => $validated['table_type'],
            'status' => $validated['status'],
            'is_active' => $request->boolean('is_active'),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.tables.index')
            ->with(
                'success',
                'Restaurant table updated successfully.'
            );
    }

    public function destroy(RestaurantTable $table)
    {
        /*
         * Later, when orders are implemented,
         * prevent deletion if this table has order history.
         */

        if ($table->status === 'occupied') {
            return back()->with(
                'error',
                'An occupied table cannot be deleted.'
            );
        }

        $table->delete();

        return redirect()
            ->route('admin.tables.index')
            ->with(
                'success',
                'Restaurant table deleted successfully.'
            );
    }
}