@extends('layouts.admin')

@section('title', 'Restaurant POS')

@section('page-title', 'Restaurant POS')

@section('content')

<div class="container-fluid px-0">

    <div
        id="cartFeedback"
        class="alert alert-success small py-2 px-3 mb-0 d-none"
        role="status"
        aria-live="polite"
        style="position: fixed; top: 84px; left: 50%; transform: translateX(-50%); z-index: 1080; width: min(440px, calc(100vw - 24px)); box-shadow: 0 8px 24px rgba(0, 0, 0, .16);">
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


    <form
        method="POST"
        action="{{ route('admin.pos.store') }}"
        id="posForm">

        @csrf

        @if($openOrder)
            <input type="hidden" name="existing_order_id" value="{{ $openOrder->id }}">
            <input type="hidden" name="order_type" value="dine_in">
            <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-info-circle"></i>
                <span>
                    Updating open order <strong>{{ $openOrder->order_number }}</strong>.
                    Changes will be saved to this order.
                </span>
            </div>
        @endif


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
                                    Branch
                                </label>
                                <select name="branch_id" id="branch_id" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" @selected((string) $selectedBranchId === (string) $branch->id)>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">

                                    Order Type

                                </label>

                                <select
                                    name="order_type"
                                    id="order_type"
                                    class="form-select"
                                    @disabled($openOrder)
                                    required>

                                    <option value="dine_in" @selected($selectedOrderType === 'dine_in')>
                                        Dine In
                                    </option>

                                    <option value="takeaway" @selected($selectedOrderType === 'takeaway')>
                                        Takeaway
                                    </option>

                                    <option value="delivery" @selected($selectedOrderType === 'delivery')>
                                        Delivery
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-3">

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

                                    @if($openOrder?->customer && !$openOrder->customer->is_active)
                                        <option value="{{ $openOrder->customer->id }}" selected>
                                            {{ $openOrder->customer->name }}
                                        </option>
                                    @endif

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
                                class="col-md-3"
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

                                        <option value="{{ $table->id }}" @selected((string) $selectedTableId === (string) $table->id)>

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

                <div id="menuItems">

                    @foreach($menuItems as $categoryName => $categoryItems)

                        <section class="menu-category-group mb-4">

                            <h5 class="fw-bold mb-3">{{ $categoryName }}</h5>

                            <div class="row g-3">

                                @foreach($categoryItems as $item)

                                    <div
                                        class="col-6 col-md-4 col-lg-3 menu-product"
                                        data-name="{{ strtolower($item->name) }}">

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

                                        @if($item->image_path)
                                            <img
                                                src="{{ asset('storage/' . $item->image_path) }}"
                                                alt="{{ $item->name }}"
                                                class="w-100 h-100 rounded-3"
                                                style="object-fit: cover;">
                                        @else
                                            <i class="bi bi-egg-fried fs-2"></i>
                                        @endif

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

                            </div>

                        </section>

                    @endforeach

                    @if($combos->isNotEmpty())

                        <section class="menu-category-group mb-4">

                            <h5 class="fw-bold mb-3">Combos</h5>

                            <div class="row g-3">

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

                        </section>

                    @endif

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
                                id="order_notes"
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

                            {{ $openOrder ? 'Update Order' : 'Place Order' }}

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
        const existingOrder = @json($existingOrderData);
        const existingOrderItems = @json($existingOrderItems);

        const discountInput =
            document.getElementById('discount');

        const discountRow =
            document.getElementById('discountRow');

        const placeOrder =
            document.getElementById('placeOrder');

        const cartFeedback =
            document.getElementById('cartFeedback');

        let cartFeedbackTimer;


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

                        cartFeedback.textContent = `Added ${name} to cart. Quantity: ${cart[key].quantity}`;
                        cartFeedback.classList.remove('d-none');
                        clearTimeout(cartFeedbackTimer);
                        cartFeedbackTimer = setTimeout(() => {
                            cartFeedback.classList.add('d-none');
                        }, 2500);


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

                    ${item.order_item_id ? `
                        <input
                            type="hidden"
                            name="items[${item.key}][order_item_id]"
                            value="${item.order_item_id}">
                    ` : ''}

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

                            product.style.display = product.dataset.name.includes(search)
                                ? ''
                                : 'none';

                        });

                    document
                        .querySelectorAll('.menu-category-group')
                        .forEach(function (group) {
                            const hasVisibleProducts = Array.from(
                                group.querySelectorAll('.menu-product')
                            ).some(product => product.style.display !== 'none');

                            group.style.display = hasVisibleProducts ? '' : 'none';
                        });

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Order Type
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('branch_id')
            .addEventListener(
                'change',
                function () {
                    const branchId = this.value;
                    const url = new URL(window.location.href);
                    if (branchId) {
                        url.searchParams.set('branch_id', branchId);
                    } else {
                        url.searchParams.delete('branch_id');
                    }
                    window.location.href = url.toString();
                }
            );

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

        if (existingOrder) {
            document.getElementById('order_type').value = 'dine_in';
            document.getElementById('customer_id').value = existingOrder.customer_id;
            document.getElementById('table_id').value = @json((string) $selectedTableId);
            document.getElementById('order_notes').value = existingOrder.notes || '';
            discountInput.value = existingOrder.discount_percent.toFixed(2);

            existingOrderItems.forEach(function (item) {
                const key = `order:${item.order_item_id}`;
                cart[key] = {
                    ...item,
                    key: key,
                };
            });
        }

        const orderTypeSelect = document.getElementById('order_type');
        const tableContainer = document.getElementById('tableContainer');
        if (orderTypeSelect.value === 'dine_in') {
            tableContainer.style.display = '';
            document.getElementById('table_id').setAttribute('required', 'required');
        } else {
            tableContainer.style.display = 'none';
            document.getElementById('table_id').removeAttribute('required');
        }

        document.getElementById('table_id').addEventListener('change', function () {
            const url = new URL(window.location.href);
            const branchId = document.getElementById('branch_id').value;
            if (branchId) {
                url.searchParams.set('branch_id', branchId);
            }
            url.searchParams.set('order_type', 'dine_in');
            if (this.value) {
                url.searchParams.set('restaurant_table_id', this.value);
            } else {
                url.searchParams.delete('restaurant_table_id');
            }
            window.location.href = url.toString();
        });

        renderCart();

    }
);

</script>

@endpush