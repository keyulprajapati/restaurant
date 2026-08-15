<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()
            ->paginate(15);

        return view(
            'admin.products.index',
            compact('products')
        );
    }

    public function create()
    {
        return view('admin.products.create');
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
                Rule::in([
                    'kg',
                    'gram',
                    'pcs',
                ]),
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
        return view(
            'admin.products.edit',
            compact('product')
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
                Rule::in([
                    'kg',
                    'gram',
                    'pcs',
                ]),
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