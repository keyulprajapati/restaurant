<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RuntimeException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $allowedBranchIds = $request->user()->allowedBranchIds();
        $products = Product::with(['unitRelation', 'branch'])
            ->when(
                $allowedBranchIds !== null,
                fn ($query) => $query->where(function ($query) use ($allowedBranchIds) {
                    $query->whereNull('branch_id')
                        ->orWhereIn('branch_id', $allowedBranchIds);
                })
            )
            ->latest()
            ->paginate(15);

        return view(
            'admin.products.index',
            compact('products')
        );
    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $branches = $this->availableBranches($request);
        $units = Unit::active()->orderBy('name')->get();

        return view('admin.products.create', compact('units', 'categories', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')->where('is_active', true),
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->user()->canAccessBranch((int) $value)) {
                        $fail('You do not have access to the selected branch.');
                    }
                },
            ],

            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
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

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            if (!is_string($imagePath)) {
                throw new RuntimeException('The product image could not be saved.');
            }
        }

        Product::create([
            'branch_id' => $validated['branch_id'],
            'category_id' => $validated['category_id'] ?? null,
            'name' => $validated['name'],
            'image_path' => $imagePath,
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

    public function edit(Request $request, Product $product)
    {
        $this->ensureProductAccess($request, $product);
        $categories = Category::orderBy('name')->get();
        $branches = $this->availableBranches($request, $product->branch_id);

        $units = Unit::where('is_active', true)
            ->orWhere('short_code', $product->unit)
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.edit',
            compact('product', 'units', 'categories', 'branches')
        );
    }

    public function update(
        Request $request,
        Product $product
    ) {
        $this->ensureProductAccess($request, $product);

        $validated = $request->validate([
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id'),
                function ($attribute, $value, $fail) use ($request, $product) {
                    $branch = Branch::find($value);
                    if ($branch && !$branch->is_active && (int) $value !== $product->branch_id) {
                        $fail('Please select an active branch.');
                    }

                    if (!$request->user()->canAccessBranch((int) $value)) {
                        $fail('You do not have access to the selected branch.');
                    }
                },
            ],

            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'remove_image' => [
                'nullable',
                'boolean',
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

        $imagePath = $product->image_path;
        if ($request->boolean('remove_image')) {
            $imagePath = null;
        }

        $previousImagePath = $product->image_path;
        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('products', 'public');
            if (!is_string($newImagePath)) {
                throw new RuntimeException('The product image could not be saved.');
            }
            $imagePath = $newImagePath;
        }

        $product->update([
            'branch_id' => $validated['branch_id'],
            'category_id' => $validated['category_id'] ?? null,
            'name' => $validated['name'],
            'image_path' => $imagePath,
            'price' => $validated['price'],
            'size' => $validated['size'] ?? null,
            'unit' => $validated['unit'],
            'food_type' => $validated['food_type'],
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($previousImagePath && $imagePath !== $previousImagePath) {
            Storage::disk('public')->delete($previousImagePath);
        }

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    public function destroy(Request $request, Product $product)
    {
        $this->ensureProductAccess($request, $product);

        if ($product->comboItems()->exists()) {

            return back()->with(
                'error',
                'This product is used in a combo and cannot be deleted.'
            );
        }

        $imagePath = $product->image_path;
        $product->delete();
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product deleted successfully.'
            );
    }

    private function availableBranches(Request $request, ?int $currentBranchId = null)
    {
        $allowedBranchIds = $request->user()->allowedBranchIds();

        return Branch::query()
            ->where(function ($query) use ($currentBranchId) {
                $query->where('is_active', true);
                if ($currentBranchId) {
                    $query->orWhere('id', $currentBranchId);
                }
            })
            ->when(
                $allowedBranchIds !== null,
                fn ($query) => $query->whereIn('id', $allowedBranchIds)
            )
            ->orderBy('name')
            ->get();
    }

    private function ensureProductAccess(Request $request, Product $product): void
    {
        abort_unless(
            $product->branch_id === null
                || $request->user()->canAccessBranch((int) $product->branch_id),
            403
        );
    }
}