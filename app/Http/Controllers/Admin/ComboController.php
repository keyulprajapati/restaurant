<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ComboController extends Controller
{
    public function index()
    {
        $combos = Combo::withCount('items')
            ->latest()
            ->paginate(15);

        return view(
            'admin.combos.index',
            compact('combos')
        );
    }

    public function create()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.combos.create',
            compact('products')
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

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.size' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.unit' => [
                'required',
                Rule::in([
                    'kg',
                    'gram',
                    'pcs',
                ]),
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.001',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $request
        ) {

            $combo = Combo::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'is_active' => $request->boolean(
                    'is_active'
                ),
            ]);

            foreach ($validated['items'] as $item) {

                $combo->items()->create([
                    'product_id' => $item['product_id'],
                    'size' => $item['size'] ?? null,
                    'unit' => $item['unit'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('admin.combos.index')
            ->with(
                'success',
                'Combo created successfully.'
            );
    }

    public function edit(Combo $combo)
    {
        $combo->load('items');

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.combos.edit',
            compact(
                'combo',
                'products'
            )
        );
    }

    public function update(
        Request $request,
        Combo $combo
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.size' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.unit' => [
                'required',
                Rule::in([
                    'kg',
                    'gram',
                    'pcs',
                ]),
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.001',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $request,
            $combo
        ) {

            $combo->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'is_active' => $request->boolean(
                    'is_active'
                ),
            ]);

            $combo->items()->delete();

            foreach ($validated['items'] as $item) {

                $combo->items()->create([
                    'product_id' => $item['product_id'],
                    'size' => $item['size'] ?? null,
                    'unit' => $item['unit'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('admin.combos.index')
            ->with(
                'success',
                'Combo updated successfully.'
            );
    }

    public function destroy(Combo $combo)
    {
        $combo->delete();

        return redirect()
            ->route('admin.combos.index')
            ->with(
                'success',
                'Combo deleted successfully.'
            );
    }
}