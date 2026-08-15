@extends('layouts.admin')

@section('title', 'Create Combo')

@section('page-title', 'Create Combo')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-10">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                <h4 class="fw-bold">
                    Create Combo
                </h4>

                <p class="text-muted mb-4">
                    Create a combo and add products to it.
                </p>


                <form
                    method="POST"
                    action="{{ route('admin.combos.store') }}">

                    @csrf


                    <div class="row g-3 mb-4">

                        <div class="col-md-8">

                            <label class="form-label fw-semibold">
                                Combo Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="e.g. Family Dinner Combo"
                                value="{{ old('name') }}"
                                required>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Combo Price
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="price"
                                    class="form-control"
                                    value="{{ old('price', 0) }}"
                                    min="0"
                                    step="0.01"
                                    required>

                            </div>

                        </div>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center mb-3">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Combo Products
                            </h5>

                            <p class="text-muted small mb-0">
                                Add products included in this combo.
                            </p>

                        </div>

                        <button
                            type="button"
                            class="btn btn-outline-danger"
                            id="addItem">

                            <i class="bi bi-plus-lg me-1"></i>

                            Add Product

                        </button>

                    </div>


                    <div
                        id="comboItems"
                        class="border rounded-4 p-3">

                    </div>


                    <div class="row mt-4">

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
                            href="{{ route('admin.combos.index') }}"
                            class="btn btn-light me-2">

                            Cancel

                        </a>

                        <button
                            class="btn btn-danger">

                            <i class="bi bi-check-lg me-2"></i>

                            Save Combo

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<template id="itemTemplate">

    <div class="combo-item border rounded-3 p-3 mb-3">

        <div class="row g-2 align-items-end">

            <div class="col-md-4">

                <label class="form-label small fw-semibold">
                    Product
                </label>

                <select
                    name="items[INDEX][product_id]"
                    class="form-select"
                    required>

                    <option value="">
                        Select Product
                    </option>

                    @foreach($products as $product)

                        <option value="{{ $product->id }}">

                            {{ $product->name }}
                            -
                            ₹{{ number_format($product->price, 2) }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-2">

                <label class="form-label small fw-semibold">
                    Size
                </label>

                <input
                    type="number"
                    name="items[INDEX][size]"
                    class="form-control"
                    min="0"
                    step="0.001">

            </div>


            <div class="col-md-2">

                <label class="form-label small fw-semibold">
                    Unit
                </label>

                <select
                    name="items[INDEX][unit]"
                    class="form-select"
                    required>

                    <option value="pcs">
                        Pcs
                    </option>

                    <option value="gram">
                        Gram
                    </option>

                    <option value="kg">
                        KG
                    </option>

                </select>

            </div>


            <div class="col-md-2">

                <label class="form-label small fw-semibold">
                    Qty
                </label>

                <input
                    type="number"
                    name="items[INDEX][quantity]"
                    class="form-control"
                    value="1"
                    min="0.001"
                    step="0.001"
                    required>

            </div>


            <div class="col-md-2">

                <button
                    type="button"
                    class="btn btn-outline-danger
                           w-100 remove-item">

                    <i class="bi bi-trash"></i>

                    Remove

                </button>

            </div>

        </div>

    </div>

</template>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        let index = 0;

        const container =
            document.getElementById('comboItems');

        const template =
            document.getElementById('itemTemplate');

        const addButton =
            document.getElementById('addItem');


        function addItem() {

            let html =
                template.innerHTML.replaceAll(
                    'INDEX',
                    index
                );

            container.insertAdjacentHTML(
                'beforeend',
                html
            );

            index++;
        }


        addButton.addEventListener(
            'click',
            addItem
        );


        container.addEventListener(
            'click',
            function (event) {

                const button =
                    event.target.closest(
                        '.remove-item'
                    );

                if (!button) {
                    return;
                }

                button
                    .closest('.combo-item')
                    .remove();

            }
        );


        // Add first item automatically.
        addItem();

    }
);

</script>

@endsection