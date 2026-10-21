@extends('layouts.admin')

@section('title', 'Add Unit')

@section('page-title', 'Add Unit')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-3"
                         style="width:52px;height:52px;">
                        <i class="bi bi-rulers fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">Add Measurement Unit</h4>
                        <p class="text-muted mb-0">Create a unit for sizing menu items, beverages, and combo components.</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Please check the errors below:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.units.store') }}">
                    @csrf

                    <div class="row g-4">
                        {{-- Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                Unit Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Glass, Pieces, Plate, Litre"
                                   maxlength="100"
                                   required
                                   autofocus>
                            <small class="text-muted">Display name shown in product selection lists.</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Short Code --}}
                        <div class="col-md-6">
                            <label for="short_code" class="form-label fw-semibold">
                                Short Code / Symbol <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="short_code"
                                   name="short_code"
                                   value="{{ old('short_code') }}"
                                   class="form-control font-monospace @error('short_code') is-invalid @enderror"
                                   placeholder="e.g. glass, pcs, plate, ltr"
                                   maxlength="50"
                                   required>
                            <small class="text-muted">Unique code used for storage and abbreviations (letters/numbers/dash).</small>
                            @error('short_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">
                                Description <span class="text-muted fw-normal">(Optional)</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Brief note on what this unit is used for...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Switch --}}
                        <div class="col-12">
                            <div class="card bg-light border-0 rounded-3 p-3">
                                <div class="form-check form-switch d-flex align-items-center gap-3 ps-0 mb-0">
                                    <input class="form-check-input ms-0"
                                           type="checkbox"
                                           role="switch"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           style="width: 2.75rem; height: 1.4rem;">
                                    <label class="form-check-label" for="is_active">
                                        <span class="fw-semibold d-block">Active Status</span>
                                        <span class="text-muted small">When active, this unit will appear in product and combo unit dropdowns.</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Form Buttons --}}
                        <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.units.index') }}" class="btn btn-light px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-check-lg me-1"></i> Save Unit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Automatically suggest short_code based on name if empty
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('short_code');
    nameInput.addEventListener('input', function() {
        if (!codeInput.dataset.touched) {
            codeInput.value = this.value.toLowerCase().trim().replace(/[^a-z0-9_-]/g, '');
        }
    });
    codeInput.addEventListener('input', function() {
        this.dataset.touched = 'true';
    });
</script>
@endpush
