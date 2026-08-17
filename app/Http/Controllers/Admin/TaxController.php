<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        $query = Tax::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        /*
        |--------------------------------------------------------------------------
        | Applies To Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('applies_to')) {
            $query->where(
                'applies_to',
                $request->applies_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Active Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('is_active')) {
            $query->where(
                'is_active',
                (bool) $request->is_active
            );
        }

        $taxes = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.taxes.index',
            compact('taxes')
        );
    }

    public function create()
    {
        return view('admin.taxes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:taxes,code',
            ],

            'type' => [
                'required',
                Rule::in([
                    'percentage',
                    'fixed',
                ]),
            ],

            'rate' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'applies_to' => [
                'required',
                Rule::in([
                    'all',
                    'food',
                    'beverage',
                    'service',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Percentage Validation
        |--------------------------------------------------------------------------
        */

        if (
            $validated['type'] === 'percentage'
            && $validated['rate'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'rate' =>
                        'Percentage tax rate cannot exceed 100.',
                ]);
        }

        $validated['code'] = strtoupper(
            $validated['code']
        );

        $validated['is_active'] =
            $request->boolean('is_active');

        Tax::create($validated);

        return redirect()
            ->route('admin.taxes.index')
            ->with(
                'success',
                'Tax created successfully.'
            );
    }

    public function edit(Tax $tax)
    {
        return view(
            'admin.taxes.edit',
            compact('tax')
        );
    }

    public function update(
        Request $request,
        Tax $tax
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',

                Rule::unique(
                    'taxes',
                    'code'
                )->ignore($tax->id),
            ],

            'type' => [
                'required',
                Rule::in([
                    'percentage',
                    'fixed',
                ]),
            ],

            'rate' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'applies_to' => [
                'required',
                Rule::in([
                    'all',
                    'food',
                    'beverage',
                    'service',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        if (
            $validated['type'] === 'percentage'
            && $validated['rate'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'rate' =>
                        'Percentage tax rate cannot exceed 100.',
                ]);
        }

        $validated['code'] = strtoupper(
            $validated['code']
        );

        $validated['is_active'] =
            $request->boolean('is_active');

        $tax->update($validated);

        return redirect()
            ->route('admin.taxes.index')
            ->with(
                'success',
                'Tax updated successfully.'
            );
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();

        return redirect()
            ->route('admin.taxes.index')
            ->with(
                'success',
                'Tax deleted successfully.'
            );
    }
}