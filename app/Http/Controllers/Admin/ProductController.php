<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('unitRelation')
            ->latest()
            ->paginate(15);

        return view(
            'admin.products.index',
            compact('products')
        );
    }

    public function create()
    {
        $units = Unit::active()->orderBy('name')->get();

        return view('admin.products.create', compact('units'));
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

            'size' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
                Rule::exists('units', 'short_code')->where('is_active', true),
            ],

            'food_type' => [
                'required',
                Rule::in([
                    'general',
                    'jain',
                ]),
            ],
        ]);

        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'size' => $validated['size'] ?? null,
            'unit' => $validated['unit'],
            'food_type' => $validated['food_type'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product created successfully.'
            );
    }

    public function edit(Product $product)
    {
        $units = Unit::where('is_active', true)
            ->orWhere('short_code', $product->unit)
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.edit',
            compact('product', 'units')
        );
    }

    public function update(
        Request $request,
        Product $product
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

            'size' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
                Rule::exists('units', 'short_code'),
            ],

            'food_type' => [
                'required',
                Rule::in([
                    'general',
                    'jain',
                ]),
            ],
        ]);

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'size' => $validated['size'] ?? null,
            'unit' => $validated['unit'],
            'food_type' => $validated['food_type'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    public function destroy(Product $product)
    {
        if ($product->comboItems()->exists()) {

            return back()->with(
                'error',
                'This product is used in a combo and cannot be deleted.'
            );
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product deleted successfully.'
            );
    }
}