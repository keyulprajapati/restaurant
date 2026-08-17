@extends('layouts.admin')

@section('title', 'Edit Tax')

@section('page-title', 'Edit Tax')


@section('content')

<div class="row justify-content-center">

    <div class="col-xl-9">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">


                <div class="d-flex align-items-center mb-4">

                    <div
                        class="rounded-3
                               bg-danger-subtle
                               text-danger
                               d-flex
                               align-items-center
                               justify-content-center
                               me-3"
                        style="width:52px;height:52px;">

                        <i class="bi bi-pencil-square fs-4"></i>

                    </div>


                    <div>

                        <h4 class="fw-bold mb-1">
                            Edit Tax
                        </h4>

                        <p class="text-muted mb-0">

                            Update
                            <strong>{{ $tax->name }}</strong>

                        </p>

                    </div>

                </div>


                @if($errors->any())

                    <div class="alert alert-danger">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        Please correct the errors below.

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route(
                        'admin.taxes.update',
                        $tax
                    ) }}">

                    @csrf

                    @method('PUT')


                    <div class="row g-4">


                        {{-- Name --}}

                        <div class="col-md-6">

                            <label
                                for="name"
                                class="form-label fw-semibold">

                                Tax Name
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old(
                                    'name',
                                    $tax->name
                                ) }}"
                                class="form-control
                                    @error('name') is-invalid @enderror"
                                maxlength="100"
                                required>

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Code --}}

                        <div class="col-md-6">

                            <label
                                for="code"
                                class="form-label fw-semibold">

                                Tax Code
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old(
                                    'code',
                                    $tax->code
                                ) }}"
                                class="form-control
                                    @error('code') is-invalid @enderror"
                                maxlength="50"
                                required>

                            @error('code')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Type --}}

                        <div class="col-md-6">

                            <label
                                for="type"
                                class="form-label fw-semibold">

                                Tax Type
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                id="type"
                                name="type"
                                class="form-select
                                    @error('type') is-invalid @enderror"
                                required>

                                <option
                                    value="percentage"
                                    @selected(
                                        old(
                                            'type',
                                            $tax->type
                                        ) === 'percentage'
                                    )>

                                    Percentage (%)

                                </option>

                                <option
                                    value="fixed"
                                    @selected(
                                        old(
                                            'type',
                                            $tax->type
                                        ) === 'fixed'
                                    )>

                                    Fixed Amount

                                </option>

                            </select>

                            @error('type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Rate --}}

                        <div class="col-md-6">

                            <label
                                for="rate"
                                class="form-label fw-semibold">

                                Rate
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    id="rate"
                                    name="rate"
                                    value="{{ old(
                                        'rate',
                                        $tax->rate
                                    ) }}"
                                    class="form-control
                                        @error('rate') is-invalid @enderror"
                                    min="0"
                                    step="0.01"
                                    required>

                                <span
                                    class="input-group-text"
                                    id="rateSuffix">

                                    %

                                </span>

                            </div>

                            <div
                                class="form-text"
                                id="rateHelp">
                            </div>

                            @error('rate')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Applies To --}}

                        <div class="col-md-6">

                            <label
                                for="applies_to"
                                class="form-label fw-semibold">

                                Applies To
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                id="applies_to"
                                name="applies_to"
                                class="form-select
                                    @error('applies_to') is-invalid @enderror"
                                required>

                                <option
                                    value="all"
                                    @selected(
                                        old(
                                            'applies_to',
                                            $tax->applies_to
                                        ) === 'all'
                                    )>

                                    All

                                </option>

                                <option
                                    value="food"
                                    @selected(
                                        old(
                                            'applies_to',
                                            $tax->applies_to
                                        ) === 'food'
                                    )>

                                    Food

                                </option>

                                <option
                                    value="beverage"
                                    @selected(
                                        old(
                                            'applies_to',
                                            $tax->applies_to
                                        ) === 'beverage'
                                    )>

                                    Beverage

                                </option>

                                <option
                                    value="service"
                                    @selected(
                                        old(
                                            'applies_to',
                                            $tax->applies_to
                                        ) === 'service'
                                    )>

                                    Service

                                </option>

                            </select>

                            @error('applies_to')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Status --}}

                        <div class="col-md-6">

                            <label
                                class="form-label fw-semibold d-block">

                                Status

                            </label>

                            <div class="form-check form-switch mt-2">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    @checked(
                                        old(
                                            'is_active',
                                            $tax->is_active
                                        )
                                    )>

                                <label
                                    class="form-check-label"
                                    for="is_active">

                                    Active

                                </label>

                            </div>

                            <small class="text-muted">
                                Inactive taxes won't be used for new orders.
                            </small>

                        </div>


                        {{-- Description --}}

                        <div class="col-12">

                            <label
                                for="description"
                                class="form-label fw-semibold">

                                Description

                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                maxlength="500"
                                class="form-control
                                    @error('description') is-invalid @enderror"
                                placeholder="Optional tax description...">{{ old(
                                    'description',
                                    $tax->description
                                ) }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="d-flex gap-2 mt-4">

                        <a
                            href="{{ route('admin.taxes.index') }}"
                            class="btn btn-light px-4">

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="btn btn-danger px-4">

                            <i class="bi bi-check-lg me-2"></i>

                            Save Changes

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const type = document.getElementById('type');
    const suffix = document.getElementById('rateSuffix');
    const help = document.getElementById('rateHelp');
    const rate = document.getElementById('rate');

    function updateRateUI()
    {
        if (type.value === 'percentage') {

            suffix.textContent = '%';

            help.textContent =
                'Enter a percentage from 0 to 100.';

            rate.max = '100';

        } else {

            suffix.textContent = 'Amount';

            help.textContent =
                'Enter the fixed tax amount.';

            rate.max = '99999999.99';

        }
    }

    type.addEventListener(
        'change',
        updateRateUI
    );

    updateRateUI();

});

</script>

@endpush

@endsection