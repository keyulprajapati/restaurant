@extends('layouts.admin')

@section('title', 'Combos')

@section('page-title', 'Combos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Combos
        </h3>

        <p class="text-muted mb-0">
            Create and manage restaurant combo offers.
        </p>

    </div>

    @can('combos.create')

        <a
            href="{{ route('admin.combos.create') }}"
            class="btn btn-danger">

            <i class="bi bi-plus-lg me-2"></i>

            Add Combo

        </a>

    @endcan

</div>


@if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

@endif


<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                <tr>

                    <th>Combo Name</th>

                    <th>Price</th>

                    <th>Products</th>

                    <th>Status</th>

                    <th class="text-end">Actions</th>

                </tr>

                </thead>

                <tbody>

                @forelse($combos as $combo)

                    <tr>

                        <td>

                            <div class="fw-semibold">

                                {{ $combo->name }}

                            </div>

                        </td>

                        <td class="fw-semibold">

                            ₹{{ number_format($combo->price, 2) }}

                        </td>

                        <td>

                            <span class="badge
                                bg-primary-subtle
                                text-primary">

                                {{ $combo->items_count }}
                                Items

                            </span>

                        </td>

                        <td>

                            @if($combo->is_active)

                                <span class="badge
                                    bg-success-subtle
                                    text-success">

                                    Active

                                </span>

                            @else

                                <span class="badge
                                    bg-secondary-subtle
                                    text-secondary">

                                    Inactive

                                </span>

                            @endif

                        </td>

                        <td class="text-end">

                            @can('combos.edit')

                                <a
                                    href="{{ route('admin.combos.edit', $combo) }}"
                                    class="btn btn-sm btn-light">

                                    <i class="bi bi-pencil"></i>

                                </a>

                            @endcan


                            @can('combos.delete')

                                <form
                                    action="{{ route('admin.combos.destroy', $combo) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this combo?')">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        class="btn btn-sm btn-light text-danger">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            @endcan

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center py-5">

                            No combos found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{ $combos->links() }}

    </div>

</div>

@endsection