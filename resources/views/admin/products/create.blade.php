@extends('layouts.admin')

@section('title', 'Add Menu Item')

@section('page-title', 'Add Menu Item')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                <h4 class="fw-bold">
                    Add Menu Item
                </h4>

                <p class="text-muted mb-4">
                    Add a product to the restaurant menu.
                </p>


                <form
                    method="POST"
                    action="{{ route('admin.products.store') }}"
                    enctype="multipart/form-data">

                    @csrf


                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="branch_id">
                                Branch
                            </label>
                            <select
                                name="branch_id"
                                id="branch_id"
                                class="form-select @error('branch_id') is-invalid @enderror"
                                required>
                                <option value="">Select branch</option>
                                @foreach($branches as $branch)
                                    <option
                                        value="{{ $branch->id }}"
                                        @selected((string) old('branch_id') === (string) $branch->id)>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">

                            <label class="form-label fw-semibold" for="category_id">
                                Category
                            </label>

                            <select
                                name="category_id"
                                id="category_id"
                                class="form-select">

                                <option value="">Select category</option>

                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Product Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control"
                                placeholder="e.g. Paneer Pizza"
                                required>

                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" for="image">
                                Product Image
                            </label>
                            <input
                                type="file"
                                name="image"
                                id="image"
                                accept="image/jpeg,image/png,image/webp"
                                class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text">JPG, PNG, or WebP; maximum 2 MB.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="imagePreview" class="mt-3 rounded-3 d-none" alt="Product image preview" style="max-width: 180px; max-height: 180px; object-fit: cover;">
                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Product Price
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="price"
                                    value="{{ old('price', 0) }}"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    required>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Size
                            </label>

                            <input
                                type="number"
                                name="size"
                                value="{{ old('size') }}"
                                class="form-control"
                                min="0"
                                step="0.001">

                        </div>


                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Unit
                            </label>

                            <select
                                name="unit"
                                class="form-select @error('unit') is-invalid @enderror">

                                @foreach($units as $unit)
                                    <option value="{{ $unit->short_code }}" @selected(old('unit', 'pcs') === $unit->short_code)>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('unit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Food Type
                            </label>

                            <div class="d-flex gap-3">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="food_type"
                                        value="general"
                                        id="general"
                                        checked>

                                    <label
                                        class="form-check-label"
                                        for="general">

                                        General

                                    </label>

                                </div>


                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="food_type"
                                        value="jain"
                                        id="jain">

                                    <label
                                        class="form-check-label"
                                        for="jain">

                                        <i class="bi bi-leaf me-1"></i>

                                        Jain

                                    </label>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <select
                                name="is_active"
                                class="form-select">

                                <option value="1">
                                    Active
                                </option>

                                <option value="0">
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="mt-4">

                        <a
                            href="{{ route('admin.products.index') }}"
                            class="btn btn-light me-2">

                            Cancel

                        </a>

                        <button
                            class="btn btn-danger">

                            <i class="bi bi-check-lg me-2"></i>

                            Save Product

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
    <script>
        document.getElementById('image')?.addEventListener('change', function () {
            const preview = document.getElementById('imagePreview');
            if (!this.files || !this.files[0]) {
                preview.src = '';
                preview.classList.add('d-none');
                return;
            }

            preview.src = URL.createObjectURL(this.files[0]);
            preview.classList.remove('d-none');
        });
    </script>
@endpush