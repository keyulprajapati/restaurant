@extends('layouts.admin')

@section('title', 'Users')

@section('page-title', 'Users')


@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Users
        </h3>

        <p class="text-muted mb-0">
            Manage restaurant staff and administrators.
        </p>

    </div>


    @can('users.create')

        <a
            href="{{ route('admin.users.create') }}"
            class="btn btn-danger">

            <i class="bi bi-person-plus me-2"></i>

            Add User

        </a>

    @endcan

</div>


@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="bi bi-exclamation-circle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                <tr>

                    <th>User</th>

                    <th>Email</th>

                    <th>Role</th>

                    <th>Created</th>

                    <th class="text-end">
                        Actions
                    </th>

                </tr>

                </thead>


                <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>

                            <div class="d-flex align-items-center">

                                <div
                                    class="rounded-circle bg-danger-subtle
                                           text-danger d-flex align-items-center
                                           justify-content-center me-3"
                                    style="width:42px;height:42px">

                                    {{ strtoupper(substr($user->name, 0, 1)) }}

                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        {{ $user->name }}
                                    </div>

                                    @if($user->id === auth()->id())

                                        <span class="badge text-bg-success">
                                            You
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </td>


                        <td>
                            {{ $user->email }}
                        </td>


                        <td>

                            @forelse($user->roles as $role)

                                <span class="badge text-bg-dark">
                                    {{ $role->name }}
                                </span>

                            @empty

                                <span class="text-muted">
                                    No role
                                </span>

                            @endforelse

                        </td>


                        <td>
                            {{ $user->created_at->format('d M Y') }}
                        </td>


                        <td class="text-end">

                            @can('users.edit')

                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="btn btn-sm btn-light">

                                    <i class="bi bi-pencil"></i>

                                </a>

                            @endcan


                            @can('users.delete')

                                @if($user->id !== auth()->id())

                                    <form
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this user?')">

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

                        <td
                            colspan="5"
                            class="text-center py-5">

                            <i
                                class="bi bi-people fs-1 text-muted">
                            </i>

                            <p class="text-muted mt-2">
                                No users found.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <div class="mt-3">

            {{ $users->links() }}

        </div>

    </div>

</div>

@endsection