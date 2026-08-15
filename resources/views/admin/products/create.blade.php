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
                    action="{{ route('admin.products.store') }}">

                    @csrf


                    <div class="row g-3">

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
                                class="form-select">

                                <option value="pcs">
                                    Pieces
                                </option>

                                <option value="gram">
                                    Gram
                                </option>

                                <option value="kg">
                                    KG
                                </option>

                            </select>

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