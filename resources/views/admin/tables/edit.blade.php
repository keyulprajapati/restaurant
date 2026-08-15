@extends('layouts.admin')

@section('title', 'Edit Restaurant Table')

@section('page-title', 'Edit Restaurant Table')

@section('content')

    <div class="row justify-content-center">

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-lg-5">

                    {{-- Header --}}

                    <div class="d-flex justify-content-between align-items-start mb-4">

                        <div>
                            <h4 class="fw-bold mb-1">
                                Edit Restaurant Table
                            </h4>

                            <p class="text-muted mb-0">
                                Update table configuration and live status.
                            </p>
                        </div>

                        <span class="badge
                            {{ $table->status === 'available'
                                ? 'bg-success-subtle text-success'
                                : ($table->status === 'occupied'
                                    ? 'bg-danger-subtle text-danger'
                                    : 'bg-warning-subtle text-warning-emphasis') }}">

                            {{ $table->status_label }}

                        </span>

                    </div>


                    {{-- Validation Errors --}}

                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <div class="fw-semibold mb-2">
                                Please fix the following errors:
                            </div>

                            <ul class="mb-0">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- Form --}}

                    <form
                        method="POST"
                        action="{{ route('admin.tables.update', $table) }}">

                        @csrf

                        @method('PUT')


                        <div class="row g-3">

                            {{-- Table Number --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Table Number
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="table_number"
                                    value="{{ old('table_number', $table->table_number) }}"
                                    class="form-control @error('table_number') is-invalid @enderror"
                                    placeholder="e.g. T01"
                                    required>

                                @error('table_number')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Table Name --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Table Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $table->name) }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="e.g. Window Table">

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Capacity --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Capacity
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <input
                                        type="number"
                                        name="capacity"
                                        value="{{ old('capacity', $table->capacity) }}"
                                        class="form-control @error('capacity') is-invalid @enderror"
                                        min="1"
                                        max="100"
                                        required>

                                    <span class="input-group-text">

                                        <i class="bi bi-people"></i>

                                    </span>

                                </div>

                                @error('capacity')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Area --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Area
                                </label>

                                <input
                                    type="text"
                                    name="area"
                                    value="{{ old('area', $table->area) }}"
                                    class="form-control @error('area') is-invalid @enderror"
                                    placeholder="e.g. Main Hall">

                                @error('area')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Table Type --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Table Type
                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    name="table_type"
                                    class="form-select @error('table_type') is-invalid @enderror"
                                    required>

                                    <option value="regular"
                                        @selected(old('table_type', $table->table_type) === 'regular')>
                                        Regular
                                    </option>

                                    <option value="round"
                                        @selected(old('table_type', $table->table_type) === 'round')>
                                        Round
                                    </option>

                                    <option value="square"
                                        @selected(old('table_type', $table->table_type) === 'square')>
                                        Square
                                    </option>

                                    <option value="outdoor"
                                        @selected(old('table_type', $table->table_type) === 'outdoor')>
                                        Outdoor
                                    </option>

                                    <option value="private"
                                        @selected(old('table_type', $table->table_type) === 'private')>
                                        Private
                                    </option>

                                </select>

                                @error('table_type')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Live Status --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Live Status

                                </label>

                                <select
                                    name="status"
                                    class="form-select @error('status') is-invalid @enderror">

                                    <option value="available"
                                        @selected(old('status', $table->status) === 'available')>

                                        Available

                                    </option>

                                    <option value="occupied"
                                        @selected(old('status', $table->status) === 'occupied')>

                                        Occupied

                                    </option>

                                    <option value="reserved"
                                        @selected(old('status', $table->status) === 'reserved')>

                                        Reserved

                                    </option>

                                </select>

                                @error('status')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Configuration Status --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Configuration Status

                                </label>

                                <select
                                    name="is_active"
                                    class="form-select">

                                    <option
                                        value="1"
                                        @selected(old('is_active', $table->is_active) == 1)>

                                        Active

                                    </option>

                                    <option
                                        value="0"
                                        @selected(old('is_active', $table->is_active) == 0)>

                                        Inactive

                                    </option>

                                </select>

                                <small class="text-muted">
                                    Inactive tables cannot be used for new orders.
                                </small>

                            </div>


                            {{-- Notes --}}

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Notes
                                </label>

                                <textarea
                                    name="notes"
                                    rows="3"
                                    class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Optional notes">{{ old('notes', $table->notes) }}</textarea>

                                @error('notes')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- Buttons --}}

                        <div class="d-flex justify-content-between mt-4">

                            <a
                                href="{{ route('admin.tables.index') }}"
                                class="btn btn-light">

                                <i class="bi bi-arrow-left me-2"></i>

                                Back

                            </a>


                            <div>

                                <a
                                    href="{{ route('admin.tables.index') }}"
                                    class="btn btn-light me-2">

                                    Cancel

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-danger">

                                    <i class="bi bi-check-lg me-2"></i>

                                    Update Table

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection