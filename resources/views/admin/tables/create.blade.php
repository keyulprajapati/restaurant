@extends('layouts.admin')

@section('title', 'Add Restaurant Table')

@section('page-title', 'Add Restaurant Table')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                <div class="mb-4">

                    <h4 class="fw-bold">
                        Add Restaurant Table
                    </h4>

                    <p class="text-muted">
                        Configure a new dining table.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('admin.tables.store') }}">

                    @csrf


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
                                value="{{ old('table_number') }}"
                                class="form-control"
                                placeholder="e.g. T01"
                                required>

                            @error('table_number')

                                <div class="text-danger small mt-1">
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
                                value="{{ old('name') }}"
                                class="form-control"
                                placeholder="e.g. Window Table">

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
                                    value="{{ old('capacity', 2) }}"
                                    class="form-control"
                                    min="1"
                                    max="100"
                                    required>

                                <span class="input-group-text">

                                    <i class="bi bi-people"></i>

                                </span>

                            </div>

                        </div>


                        {{-- Area --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Area
                            </label>

                            <input
                                type="text"
                                name="area"
                                value="{{ old('area') }}"
                                class="form-control"
                                placeholder="e.g. Main Hall">

                        </div>


                        {{-- Type --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Table Type
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="table_type"
                                class="form-select"
                                required>

                                <option value="regular">
                                    Regular
                                </option>

                                <option value="round">
                                    Round
                                </option>

                                <option value="square">
                                    Square
                                </option>

                                <option value="outdoor">
                                    Outdoor
                                </option>

                                <option value="private">
                                    Private
                                </option>

                            </select>

                        </div>


                        {{-- Status --}}

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


                        {{-- Notes --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                                class="form-control"
                                placeholder="Optional notes">{{ old('notes') }}</textarea>

                        </div>

                    </div>


                    <div class="mt-4">

                        <a
                            href="{{ route('admin.tables.index') }}"
                            class="btn btn-light me-2">

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="btn btn-danger">

                            <i class="bi bi-check-lg me-2"></i>

                            Save Table

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection