@extends('layouts.admin')

@section('title', 'Permissions')

@section('page-title', 'Permissions')


@section('content')

<div class="mb-4">

    <h3 class="fw-bold mb-1">
        Permissions
    </h3>

    <p class="text-muted mb-0">
        Available system permissions.
    </p>

</div>


<div class="row g-4">

    @foreach(
        $permissions->groupBy(
            fn($permission) =>
            explode('.', $permission->name)[0]
        )
        as $group => $groupPermissions
    )

        <div class="col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="rounded-3 bg-danger-subtle
                                   text-danger p-2 me-3">

                            <i class="bi bi-shield-lock fs-5"></i>

                        </div>

                        <h5 class="fw-bold mb-0 text-capitalize">

                            {{ $group }}

                        </h5>

                    </div>


                    @foreach($groupPermissions as $permission)

                        <div
                            class="d-flex align-items-center
                                   justify-content-between
                                   border-bottom py-2">

                            <span class="small">

                                {{ $permission->name }}

                            </span>

                            <i
                                class="bi bi-check-circle-fill
                                       text-success">
                            </i>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @endforeach

</div>

@endsection