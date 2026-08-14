@extends('layouts.admin')

@section('title', 'Categories')

@section('page-title', 'Categories')


@section('content')

<div class="d-flex flex-wrap justify-content-between
            align-items-center gap-3 mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Categories
        </h3>

        <p class="text-muted mb-0">
            Manage food and menu categories.
        </p>

    </div>


    @can('categories.create')

        <a
            href="{{ route('admin.categories.create') }}"
            class="btn btn-danger">

            <i class="bi bi-plus-lg me-2"></i>

            Add Category

        </a>

    @endcan

</div>


{{-- Success Message --}}

@if(session('success'))

    <div
        class="alert alert-success alert-dismissible
               fade show border-0 shadow-sm">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- Error Message --}}

@if(session('error'))

    <div
        class="alert alert-danger alert-dismissible
               fade show border-0 shadow-sm">

        <i class="bi bi-exclamation-circle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- Search & Filter --}}

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-3">

        <form
            method="GET"
            action="{{ route('admin.categories.index') }}">

            <div class="row g-2">


                {{-- Search --}}

                <div class="col-lg-6">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search category...">

                    </div>

                </div>


                {{-- Status --}}

                <div class="col-lg-3">

                    <select
                        name="status"
                        class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="active"
                            @selected(request('status') === 'active')>

                            Active

                        </option>

                        <option
                            value="inactive"
                            @selected(request('status') === 'inactive')>

                            Inactive

                        </option>

                    </select>

                </div>


                {{-- Buttons --}}

                <div class="col-lg-3 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-dark flex-fill">

                        <i class="bi bi-funnel me-1"></i>

                        Filter

                    </button>


                    @if(request()->hasAny(['search', 'status']))

                        <a
                            href="{{ route('admin.categories.index') }}"
                            class="btn btn-light">

                            <i class="bi bi-x-lg"></i>

                        </a>

                    @endif

                </div>

            </div>

        </form>

    </div>

</div>


{{-- Categories Table --}}

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="bg-light">

                <tr>

                    <th class="px-4">
                        #
                    </th>

                    <th>
                        Category Name
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Created
                    </th>

                    <th class="text-end px-4">
                        Actions
                    </th>

                </tr>

                </thead>


                <tbody>

                @forelse($categories as $category)

                    <tr>

                        <td class="px-4 text-muted">

                            {{ $categories->firstItem() + $loop->index }}

                        </td>


                        <td>

                            <div class="d-flex align-items-center">

                                <div
                                    class="rounded-3
                                           bg-danger-subtle
                                           text-danger
                                           d-flex
                                           align-items-center
                                           justify-content-center
                                           me-3"
                                    style="width:42px;height:42px;">

                                    <i class="bi bi-tags"></i>

                                </div>


                                <div>

                                    <div class="fw-semibold">

                                        {{ $category->name }}

                                    </div>

                                    <small class="text-muted">

                                        Category #{{ $category->id }}

                                    </small>

                                </div>

                            </div>

                        </td>


                        <td>

                            @if($category->status === 'active')

                                <span
                                    class="badge rounded-pill
                                           bg-success-subtle
                                           text-success
                                           px-3 py-2">

                                    <i
                                        class="bi bi-check-circle me-1">
                                    </i>

                                    Active

                                </span>

                            @else

                                <span
                                    class="badge rounded-pill
                                           bg-secondary-subtle
                                           text-secondary
                                           px-3 py-2">

                                    <i
                                        class="bi bi-pause-circle me-1">
                                    </i>

                                    Inactive

                                </span>

                            @endif

                        </td>


                        <td>

                            <span class="text-muted">

                                {{ $category->created_at->format('d M Y') }}

                            </span>

                        </td>


                        <td class="text-end px-4">

                            @can('categories.edit')

                                <a
                                    href="{{ route(
                                        'admin.categories.edit',
                                        $category
                                    ) }}"
                                    class="btn btn-sm btn-light"
                                    title="Edit">

                                    <i class="bi bi-pencil"></i>

                                </a>

                            @endcan


                            @can('categories.delete')

                                <form
                                    action="{{ route(
                                        'admin.categories.destroy',
                                        $category
                                    ) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm(
                                        'Are you sure you want to delete this category?'
                                    )">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-light
                                               text-danger"
                                        title="Delete">

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

                            <div class="mb-3">

                                <i
                                    class="bi bi-tags
                                           display-5
                                           text-muted">
                                </i>

                            </div>

                            <h6 class="fw-semibold">
                                No categories found
                            </h6>

                            <p class="text-muted mb-0">
                                Create your first menu category.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    @if($categories->hasPages())

        <div class="card-footer bg-white border-0 p-4">

            {{ $categories->links() }}

        </div>

    @endif

</div>

@endsection