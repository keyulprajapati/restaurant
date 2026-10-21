<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboItem;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::withCount(['products', 'comboItems']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $units = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Unit::count(),
            'active' => Unit::where('is_active', true)->count(),
            'inactive' => Unit::where('is_active', false)->count(),
        ];

        return view('admin.units.index', compact('units', 'stats'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:units,name',
            ],
            'short_code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:units,short_code',
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

        $shortCode = Str::lower(trim($validated['short_code']));

        Unit::create([
            'name' => trim($validated['name']),
            'short_code' => $shortCode,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit "' . $validated['name'] . '" created successfully.');
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('units', 'name')->ignore($unit->id),
            ],
            'short_code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('units', 'short_code')->ignore($unit->id),
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

        $oldShortCode = $unit->short_code;
        $newShortCode = Str::lower(trim($validated['short_code']));

        DB::transaction(function () use ($unit, $validated, $oldShortCode, $newShortCode, $request) {
            $unit->update([
                'name' => trim($validated['name']),
                'short_code' => $newShortCode,
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            // If short code changed, update referencing products and combos
            if ($oldShortCode !== $newShortCode) {
                Product::where('unit', $oldShortCode)->update(['unit' => $newShortCode]);
                ComboItem::where('unit', $oldShortCode)->update(['unit' => $newShortCode]);
            }
        });

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit "' . $unit->name . '" updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->isInUse()) {
            return redirect()
                ->route('admin.units.index')
                ->with('error', 'Cannot delete unit "' . $unit->name . '" because it is currently assigned to products or combo items. You can set it to inactive instead.');
        }

        $unitName = $unit->name;
        $unit->delete();

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit "' . $unitName . '" deleted successfully.');
    }

    public function toggleStatus(Unit $unit)
    {
        $unit->is_active = !$unit->is_active;
        $unit->save();

        $statusText = $unit->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Unit \"{$unit->name}\" {$statusText} successfully.");
    }
}
