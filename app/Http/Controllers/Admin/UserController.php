<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $branches = Branch::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'all_branches' => ['sometimes', 'boolean'],
            'branch_ids' => ['required_unless:all_branches,1', 'array', 'min:1'],
            'branch_ids.*' => ['integer', 'distinct', 'exists:branches,id'],
            'aadhar_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s-]+$/'],
            'pan_number' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/'],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'branch_id' => $request->boolean('all_branches') ? null : ($validated['branch_ids'][0] ?? null),
            'all_branches' => $request->boolean('all_branches'),
            'aadhar_number' => $validated['aadhar_number'] ?? null,
            'pan_number' => $validated['pan_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $user->branches()->sync($request->boolean('all_branches') ? [] : $validated['branch_ids']);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $branches = Branch::query()->where('is_active', true)->orderBy('name')->get();

        $userRole = $user->roles->first()?->name;
        $assignedBranchIds = $user->branches()->pluck('branches.id')->all();
        if ($user->branch_id && !in_array($user->branch_id, $assignedBranchIds)) {
            $assignedBranchIds[] = $user->branch_id;
        }

        return view(
            'admin.users.edit',
            compact('user', 'roles', 'userRole', 'branches', 'assignedBranchIds')
        );
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'all_branches' => ['sometimes', 'boolean'],
            'branch_ids' => ['required_unless:all_branches,1', 'array', 'min:1'],
            'branch_ids.*' => ['integer', 'distinct', 'exists:branches,id'],
            'aadhar_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s-]+$/'],
            'pan_number' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/'],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->all_branches = $request->boolean('all_branches');
        $user->branch_id = $user->all_branches ? null : ($validated['branch_ids'][0] ?? null);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->branches()->sync($user->all_branches ? [] : $validated['branch_ids']);

        $user->syncRoles([
            $validated['role']
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}