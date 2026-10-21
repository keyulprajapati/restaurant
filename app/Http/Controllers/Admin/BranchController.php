<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->boolean('status'));
        }

        $branches = $query->latest()->paginate(15)->withQueryString();

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:branches,code'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'manager_name' => ['nullable', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ]);

        Branch::create($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', Rule::unique('branches', 'code')->ignore($branch->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'manager_name' => ['nullable', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ]);

        $branch->update($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->tables()->exists() || $branch->orders()->exists()) {
            return back()->with('error', 'This branch cannot be deleted because it has linked tables or orders.');
        }

        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }
}
