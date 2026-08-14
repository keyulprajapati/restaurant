@extends('layouts.admin')

@section('title', 'Roles')

@section('page-title', 'Roles')


@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Roles
        </h3>

        <p class="text-muted mb-0">
            Manage roles and their permissions.
        </p>

    </div>


    @can('roles.create')

        <a
            href="{{ route('admin.roles.create') }}"
            class="btn btn-danger">

            <i class="bi bi-shield-plus me-2"></i>

            Create Role

        </a>

    @endcan

</div>


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger">
        {{ session('error') }}
    </div>

@endif


<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                <tr>

                    <th>
                        Role
                    </th>

                    <th>
                        Users
                    </th>

                    <th>
                        Permissions
                    </th>

                    <th class="text-end">
                        Actions
                    </th>

                </tr>

                </thead>


                <tbody>

                @forelse($roles as $role)

                    <tr>

                        <td>

                            <span class="fw-semibold">

                                <i class="bi bi-shield-check me-2"></i>

                                {{ $role->name }}

                            </span>

                            @if($role->name === 'Super Admin')

                                <span class="badge text-bg-danger ms-2">
                                    System
                                </span>

                            @endif

                        </td>


                        <td>

                            <span class="badge bg-primary-subtle text-primary">

                                {{ $role->users_count }}

                            </span>

                        </td>


                        <td>

                            <span class="badge bg-dark-subtle text-dark">

                                {{ $role->permissions->count() }}

                                permissions

                            </span>

                        </td>


                        <td class="text-end">

                            @can('roles.edit')

                                <a
                                    href="{{ route('admin.roles.edit', $role) }}"
                                    class="btn btn-sm btn-light">

                                    <i class="bi bi-pencil"></i>

                                </a>

                            @endcan


                            @can('roles.delete')

                                @if($role->name !== 'Super Admin')

                                    <form
                                        action="{{ route('admin.roles.destroy', $role) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this role?')">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-light text-danger">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                @endif

                            @endcan

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4"
                            class="text-center py-5">

                            No roles found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{ $roles->links() }}

    </div>

</div>

@endsection