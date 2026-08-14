@extends('layouts.admin')

@section('title', 'Create Role')

@section('page-title', 'Create Role')


@section('content')

<div class="row justify-content-center">

    <div class="col-xl-9">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                <h4 class="fw-bold">
                    Create Role
                </h4>

                <p class="text-muted">
                    Create a role and assign permissions.
                </p>


                <form
                    method="POST"
                    action="{{ route('admin.roles.store') }}">

                    @csrf


                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Role Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control"
                            placeholder="e.g. Restaurant Manager"
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
                                                @checked(in_array(
                                                    $permission->name,
                                                    old('permissions', [])
                                                ))>

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

                            <i class="bi bi-shield-check me-2"></i>

                            Create Role

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection