<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
         'name' => [
        'required',
        'string',
        'max:100',
    ],

    'email' => [
        'required',
        'email',
        'max:100',
        'unique:users,email',
    ],

    'aadhar_number' => [
        'nullable',
        'string',
        'max:20',
        'regex:/^[0-9\s-]+$/',
    ],

    'pan_number' => [
        'nullable',
        'string',
        'max:20',
        'regex:/^[A-Za-z0-9]+$/',
    ],

    'address' => [
        'nullable',
        'string',
        'max:1000',
    ],

    'password' => [
        'required',
        'string',
        'min:8',
        'confirmed',
    ],

    'role' => [
        'required',
        'exists:roles,name',
    ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
    'email' => $validated['email'],

    'aadhar_number' =>
        $validated['aadhar_number'] ?? null,

    'pan_number' =>
        $validated['pan_number'] ?? null,

    'address' =>
        $validated['address'] ?? null,

    'password' => Hash::make(
        $validated['password']),
        ]);

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

        $userRole = $user->roles->first()?->name;

        return view(
            'admin.users.edit',
            compact('user', 'roles', 'userRole')
        );
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
       'name' => [
        'required',
        'string',
        'max:100',
    ],

    'email' => [
        'required',
        'email',
        'max:100',
        Rule::unique('users', 'email')
            ->ignore($user->id),
    ],

    'aadhar_number' => [
        'nullable',
        'string',
        'max:20',
        'regex:/^[0-9\s-]+$/',
    ],

    'pan_number' => [
        'nullable',
        'string',
        'max:20',
        'regex:/^[A-Za-z0-9]+$/',
    ],

    'address' => [
        'nullable',
        'string',
        'max:1000',
    ],

    'password' => [
        'nullable',
        'string',
        'min:8',
        'confirmed',
    ],

    'role' => [
        'required',
        'exists:roles,name',
    ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make(
                $validated['password']
            );
        }

        $user->save();

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