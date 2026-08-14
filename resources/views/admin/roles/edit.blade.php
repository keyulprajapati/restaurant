@extends('layouts.admin')

@section('title', 'Edit Role')

@section('page-title', 'Edit Role')


@section('content')

<div class="row justify-content-center">

    <div class="col-xl-9">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                <h4 class="fw-bold">
                    Edit Role
                </h4>

                <p class="text-muted">
                    Update role permissions.
                </p>


                <form
                    method="POST"
                    action="{{ route('admin.roles.update', $role) }}">

                    @csrf

                    @method('PUT')


                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Role Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $role->name) }}"
                            class="form-control"
                            required>

                    </div>


                    <h6 class="fw-bold mb-3">
                        Permissions
                    </h6>


                    <div class="row g-3">

                        @foreach($permissions->groupBy(fn($permission) => explode('.', $permission->name)[0]) as $group => $groupPermissions)

                            <div class="col-md-6">

                                <div class="border rounded-3 p-3">

                                    <div class="fw-semibold text-capitalize mb-2">

                                        {{ $group }}

                                    </div>


                                    @foreach($groupPermissions as $permission)

                                        <div class="form-check">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->name }}"
                                                id="permission_{{ $permission->id }}"

                                                @checked(
                                                    in_array(
                                                        $permission->name,
                                                        old(
                                                            'permissions',
                                                            $rolePermissions
                                                        )
                                                    )
                                                )
                                            >

                                            <label
                                                class="form-check-label"
                                                for="permission_{{ $permission->id }}">

                                                {{ $permission->name }}

                                            </label>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    </div>


                    <div class="mt-4">

                        <a
                            href="{{ route('admin.roles.index') }}"
                            class="btn btn-light me-2">

                            Cancel

                        </a>

                        <button
                            class="btn btn-danger">

                            <i class="bi bi-check-lg me-2"></i>

                            Save Changes

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection