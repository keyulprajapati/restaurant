@extends('layouts.admin')

@section('title', 'Menu Items')

@section('page-title', 'Menu Items')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Menu Items
        </h3>

        <p class="text-muted mb-0">
            Manage restaurant food and beverage products.
        </p>
    </div>

    @can('products.create')

        <a
            href="{{ route('admin.products.create') }}"
            class="btn btn-danger">

            <i class="bi bi-plus-lg me-2"></i>

            Add Product

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

                    <th>Product</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Food Type</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>

                </tr>

                </thead>

                <tbody>

                @forelse($products as $product)

                    <tr>

                        <td>

                            <div class="fw-semibold">
                                {{ $product->name }}
                            </div>

                        </td>

                        <td class="fw-semibold">

                            ₹{{ number_format($product->price, 2) }}

                        </td>

                        <td>

                            {{ $product->formatted_size }}

                        </td>

                        <td>

                            @if($product->food_type === 'jain')

                                <span class="badge
                                    bg-success-subtle
                                    text-success">

                                    <i class="bi bi-leaf me-1"></i>
                                    Jain

                                </span>

                            @else

                                <span class="badge
                                    bg-secondary-subtle
                                    text-secondary">

                                    General

                                </span>

                            @endif

                        </td>

                        <td>

                            @if($product->is_active)

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

                            @can('products.edit')

                                <a
                                    href="{{ route('admin.products.edit', $product) }}"
                                    class="btn btn-sm btn-light">

                                    <i class="bi bi-pencil"></i>

                                </a>

                            @endcan

                            @can('products.delete')

                                <form
                                    action="{{ route('admin.products.destroy', $product) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this product?')">

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

                        <td colspan="6"
                            class="text-center py-5">

                            <i class="bi bi-basket fs-1 text-muted"></i>

                            <p class="text-muted mt-2">
                                No menu items found.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{ $products->links() }}

    </div>

</div>

@endsection