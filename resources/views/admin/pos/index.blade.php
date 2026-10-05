@extends('layouts.admin')

@section('title', 'Restaurant POS')

@section('page-title', 'Restaurant POS')

@section('content')

<div class="container-fluid px-0">

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


    <form
        method="POST"
        action="{{ route('admin.pos.store') }}"
        id="posForm">

        @csrf


        <div class="row g-4">


            {{-- =====================================================
                 MENU
            ====================================================== --}}

            <div class="col-xl-8">


                {{-- Order Type / Customer / Table --}}

                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body">

                        <div class="row g-3">


                            <div class="col-md-3">

                                <label class="form-label fw-semibold">

                                    Order Type

                                </label>

                                <select
                                    name="order_type"
                                    id="order_type"
                                    class="form-select"
                                    required>

                                    <option value="dine_in">
                                        Dine In
                                    </option>

                                    <option value="takeaway">
                                        Takeaway
                                    </option>

                                    <option value="delivery">
                                        Delivery
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Customer

                                </label>

                                <select
                                    name="customer_id"
                                    id="customer_id"
                                    class="form-select"
                                    required>

                                    <option value="walk_in">
                                        Walk-In Customer
                                    </option>

                                    @foreach(
                                        \App\Models\Customer::where(
                                            'is_active',
                                            true
                                        )->orderBy('name')->get()
                                        as $customer
                                    )

                                        <option value="{{ $customer->id }}">

                                            {{ $customer->name }}
                                            -
                                            {{ $customer->phone }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div
                                class="col-md-5"
                                id="tableContainer">

                                <label class="form-label fw-semibold">

                                    Table

                                </label>

                                <select
                                    name="restaurant_table_id"
                                    id="table_id"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Table
                                    </option>

                                    @foreach($tables as $table)

                                        <option value="{{ $table->id }}">

                                            {{ $table->table_number }}

                                            @if($table->name)
                                                - {{ $table->name }}
                                            @endif

                                            ({{ $table->capacity }} seats)

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Search --}}

                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body">

                        <div class="input-group">

                            <span class="input-group-text bg-white">

                                <i class="bi bi-search"></i>

                            </span>

                            <input
                                type="text"
                                id="menuSearch"
                                class="form-control"
                                placeholder="Search menu items...">

                        </div>

                    </div>

                </div>


                {{-- Menu Items --}}

                <div class="row g-3" id="menuItems">

                    @foreach($menuItems as $item)

                        <div
                            class="col-6 col-md-4 col-lg-3 menu-product"
                            data-name="{{ strtolower(
                                $item->name
                            ) }}">

                            <button
                                type="button"
                                class="card border-0 shadow-sm rounded-4
                                       w-100 text-start h-100
                                       product-button"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-size="{{ $item->size }}"
                                data-unit="{{ $item->unit }}"
                                data-price="{{ $item->price }}"
                                data-type="product">

                                <div class="card-body">

                                    <div
                                        class="rounded-3 bg-danger-subtle
                                               text-danger
                                               d-flex align-items-center
                                               justify-content-center mb-3"
                                        style="height:70px;">

                                        <i class="bi bi-egg-fried fs-2"></i>

                                    </div>

                                    <div class="fw-semibold">

                                        {{ $item->name }}

                                    </div>

                                    @if($item->size)

                                        <small class="text-muted">

                                            {{ rtrim(rtrim(number_format((float) $item->size, 3, '.', ''), '0'), '.') }}
                                            {{ $item->unit ? ' - ' . $item->unit : '' }}

                                        </small>

                                    @endif

                                    <div class="fw-bold mt-2">

                                        ₹{{ number_format(
                                            (float) $item->price,
                                            2
                                        ) }}

                                    </div>

                                </div>

                            </button>

                        </div>

                    @endforeach

                    @foreach($combos as $combo)

                        <div
                            class="col-6 col-md-4 col-lg-3 menu-product"
                            data-name="{{ strtolower($combo->name) }}">

                            <button
                                type="button"
                                class="card border-0 shadow-sm rounded-4
                                       w-100 text-start h-100
                                       product-button"
                                data-id="{{ $combo->id }}"
                                data-name="{{ $combo->name }}"
                                data-size=""
                                data-unit="combo"
                                data-price="{{ $combo->price }}"
                                data-type="combo">

                                <div class="card-body">

                                    <div
                                        class="rounded-3 bg-warning-subtle
                                               text-warning
                                               d-flex align-items-center
                                               justify-content-center mb-3"
                                        style="height:70px;">

                                        <i class="bi bi-basket3 fs-2"></i>

                                    </div>

                                    <div class="fw-semibold">

                                        {{ $combo->name }}

                                    </div>

                                    <small class="text-muted d-block mt-1">
                                        Combo
                                    </small>

                                    <div class="fw-bold mt-2">

                                        ₹{{ number_format(
                                            (float) $combo->price,
                                            2
                                        ) }}

                                    </div>

                                </div>

                            </button>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- =====================================================
                 CART
            ====================================================== --}}

            <div class="col-xl-4">

                <div
                    class="card border-0 shadow-sm rounded-4 sticky-top"
                    style="top:20px;">


                    <div class="card-header bg-white border-0 p-4">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h5 class="fw-bold mb-1">

                                    Current Order

                                </h5>

                                <small class="text-muted">

                                    Add items to order

                                </small>

                            </div>


                            <button
                                type="button"
                                class="btn btn-sm btn-light"
                                id="clearCart">

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    </div>


                    <div class="card-body p-4">

                        <div
                            id="cartItems"
                            style="max-height:400px;overflow-y:auto;">

                            <div
                                id="emptyCart"
                                class="text-center text-muted py-5">

                                <i
                                    class="bi bi-cart3 fs-1 d-block mb-3">
                                </i>

                                No items added.

                            </div>

                        </div>


                        <hr>


                        {{-- Discount --}}

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Discount (%)

                            </label>

                            <input
                                type="number"
                                name="discount"
                                id="discount"
                                value="0"
                                min="0"
                                max="100"
                                step="0.01"
                                class="form-control"
                                placeholder="0">

                        </div>


                        {{-- Notes --}}

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Order Notes

                            </label>

                            <textarea
                                name="notes"
                                class="form-control"
                                rows="2"
                                placeholder="Special instructions..."></textarea>

                        </div>


                        {{-- Totals --}}

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Subtotal
                            </span>

                            <span
                                id="subtotal"
                                class="fw-semibold">

                                ₹0.00

                            </span>

                        </div>


                        <div id="discountRow" class="d-flex justify-content-between mb-2" style="display: none;">

                            <span class="text-muted">
                                Discount
                            </span>

                            <span
                                id="discountAmount"
                                class="text-danger">

                                - ₹0.00

                            </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Tax
                            </span>

                            <span id="taxAmount">
                                ₹0.00
                            </span>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="fw-bold fs-5">
                                Grand Total
                            </span>

                            <span
                                id="grandTotal"
                                class="fw-bold fs-5 text-danger">

                                ₹0.00

                            </span>

                        </div>


                        {{-- Submit --}}

                        <button
                            type="submit"
                            id="placeOrder"
                            class="btn btn-danger w-100 mt-4 py-3"
                            disabled>

                            <i class="bi bi-check2-circle me-2"></i>

                            Place Order

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const cart = {};


        const cartItems =
            document.getElementById('cartItems');

        const emptyCart =
            document.getElementById('emptyCart');

        const subtotalElement =
            document.getElementById('subtotal');

        const discountElement =
            document.getElementById('discountAmount');

        const taxElement =
            document.getElementById('taxAmount');

        const grandTotalElement =
            document.getElementById('grandTotal');

        const activeTaxes = @json($taxes ?? []);

        const discountInput =
            document.getElementById('discount');

        const discountRow =
            document.getElementById('discountRow');

        const placeOrder =
            document.getElementById('placeOrder');


        /*
        |--------------------------------------------------------------------------
        | Add Product
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.product-button')
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const id =
                            this.dataset.id;

                        const type =
                            this.dataset.type || 'product';

                        const key =
                            `${type}:${id}`;

                        const name =
                            this.dataset.name;

                        const size =
                            this.dataset.size;

                        const unit =
                            this.dataset.unit;

                        const price =
                            parseFloat(
                                this.dataset.price
                            );


                        if (!cart[key]) {

                            cart[key] = {

                                key: key,
                                id: id,
                                type: type,
                                name: name,
                                size: size,
                                unit: unit,
                                price: price,
                                quantity: 1

                            };

                        } else {

                            cart[key].quantity++;

                        }


                        renderCart();

                    }
                );

            });


        /*
        |--------------------------------------------------------------------------
        | Render Cart
        |--------------------------------------------------------------------------
        */

        function renderCart()
        {
            cartItems.innerHTML = '';


            const items =
                Object.values(cart);


            if (!items.length) {

                cartItems.innerHTML = `

                    <div
                        class="text-center text-muted py-5">

                        <i
                            class="bi bi-cart3 fs-1 d-block mb-3">
                        </i>

                        No items added.

                    </div>

                `;

                placeOrder.disabled = true;

                updateTotals();

                return;

            }


            placeOrder.disabled = false;


            items.forEach(function (item) {

                const row =
                    document.createElement('div');


                row.className =
                    'border-bottom pb-3 mb-3';


                row.innerHTML = `

                    <div
                        class="d-flex justify-content-between">

                        <div>

                            <div class="fw-semibold">

                                ${escapeHtml(item.name)}

                            </div>

                            <small class="text-muted">

                                ${escapeHtml(formatDisplayNumber(item.size))}
                                ${escapeHtml(item.unit ? ' - ' + item.unit : '')}

                            </small>

                        </div>


                        <button
                            type="button"
                            class="btn btn-sm text-danger remove-item"
                            data-key="${item.key}">

                            <i class="bi bi-x-lg"></i>

                        </button>

                    </div>


                    <div
                        class="d-flex justify-content-between
                               align-items-center mt-2">

                        <div
                            class="btn-group btn-group-sm">

                            <button
                                type="button"
                                class="btn btn-light decrease"
                                data-key="${item.key}">

                                −

                            </button>

                            <span
                                class="btn btn-light disabled">

                                ${item.quantity}

                            </span>

                            <button
                                type="button"
                                class="btn btn-light increase"
                                data-key="${item.key}">

                                +

                            </button>

                        </div>


                        <strong>

                            ₹${(
                                item.price *
                                item.quantity
                            ).toFixed(2)}

                        </strong>

                    </div>

                    <input
                        type="hidden"
                        name="items[${item.key}][id]"
                        value="${item.id}">

                    <input
                        type="hidden"
                        name="items[${item.key}][type]"
                        value="${item.type}">

                    <input
                        type="hidden"
                        name="items[${item.key}][${item.type === 'combo' ? 'combo_id' : 'menu_item_id'}]"
                        value="${item.id}">

                    <input
                        type="hidden"
                        name="items[${item.key}][quantity]"
                        value="${item.quantity}">

                `;


                cartItems.appendChild(row);

            });


            /*
             * Increase
             */

            document
                .querySelectorAll('.increase')
                .forEach(function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const key = this.dataset.key;

                            if (cart[key]) {
                                cart[key].quantity++;
                                renderCart();
                            }

                        }
                    );

                });


            /*
             * Decrease
             */

            document
                .querySelectorAll('.decrease')
                .forEach(function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const key = this.dataset.key;

                            if (!cart[key]) {
                                return;
                            }

                            cart[key].quantity--;

                            if (
                                cart[key].quantity <= 0
                            ) {

                                delete cart[key];

                            }


                            renderCart();

                        }
                    );

                });


            /*
             * Remove
             */

            document
                .querySelectorAll('.remove-item')
                .forEach(function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            delete cart[
                                this.dataset.key
                            ];

                            renderCart();

                        }
                    );

                });


            updateTotals();
        }


        /*
        |--------------------------------------------------------------------------
        | Totals
        |--------------------------------------------------------------------------
        */

        function updateTotals()
        {
            let subtotal = 0;


            Object.values(cart)
                .forEach(function (item) {

                    subtotal +=
                        item.price *
                        item.quantity;

                });


            const discountPercent =
                parseFloat(
                    discountInput.value
                ) || 0;

            const discount =
                subtotal > 0
                    ? Math.min(
                        subtotal,
                        (subtotal * discountPercent) / 100
                    )
                    : 0;

            const taxableAmount =
                Math.max(0, subtotal - discount);

            if (discountPercent > 0) {
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }

            let tax = 0;
            if (taxableAmount > 0 && Array.isArray(activeTaxes)) {
                activeTaxes.forEach(function (rule) {
                    const rate = parseFloat(rule.rate) || 0;
                    if (rule.type === 'percentage') {
                        tax += taxableAmount * (rate / 100);
                    } else if (rule.type === 'fixed') {
                        tax += rate;
                    }
                });
            }

            const grandTotal =
                taxableAmount + tax;

            subtotalElement.innerText =
                '₹' + subtotal.toFixed(2);

            discountElement.innerText =
                '- ₹' + discount.toFixed(2);

            if (taxElement) {
                taxElement.innerText =
                    '₹' + tax.toFixed(2);
            }

            grandTotalElement.innerText =
                '₹' + grandTotal.toFixed(2);

        }


        discountInput.addEventListener(
            'input',
            updateTotals
        );


        /*
        |--------------------------------------------------------------------------
        | Clear Cart
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('clearCart')
            .addEventListener(
                'click',
                function () {

                    Object.keys(cart)
                        .forEach(
                            key => delete cart[key]
                        );

                    renderCart();

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Menu Search
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('menuSearch')
            .addEventListener(
                'input',
                function () {

                    const search =
                        this.value
                            .toLowerCase()
                            .trim();


                    document
                        .querySelectorAll(
                            '.menu-product'
                        )
                        .forEach(function (product) {

                            product.style.display =
                                product.dataset.name
                                    .includes(search)
                                    ? ''
                                    : 'none';

                        });

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Order Type
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('order_type')
            .addEventListener(
                'change',
                function () {

                    const tableContainer =
                        document.getElementById(
                            'tableContainer'
                        );


                    const tableSelect = document.getElementById('table_id');

                    if (
                        this.value === 'dine_in'
                    ) {

                        tableContainer.style.display = '';
                        tableSelect.setAttribute('required', 'required');

                    } else {

                        tableContainer.style.display = 'none';
                        tableSelect.value = '';
                        tableSelect.removeAttribute('required');

                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | HTML escape
        |--------------------------------------------------------------------------
        */

        function formatDisplayNumber(value)
        {
            if (value === null || value === undefined || value === '') {
                return '';
            }

            const numeric = Number(value);

            if (Number.isNaN(numeric)) {
                return String(value);
            }

            if (Number.isInteger(numeric)) {
                return String(numeric);
            }

            return String(Number(numeric.toFixed(3))).replace(/\.0+$|(?<=\.[0-9]*?)0+$/, '');
        }

        function escapeHtml(value)
        {
            return String(value ?? '')
                .replace(
                    /&/g,
                    '&amp;'
                )
                .replace(
                    /</g,
                    '&lt;'
                )
                .replace(
                    />/g,
                    '&gt;'
                )
                .replace(
                    /"/g,
                    '&quot;'
                )
                .replace(
                    /'/g,
                    '&#039;'
                );
        }

    }
);

</script>

@endpush