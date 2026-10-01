@extends('layouts.admin')

@section('title', 'Add Extra Tables')
@section('page-title', 'Add Extra Tables')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-grid-plus me-2 text-danger"></i>Add Extra Tables
        </h3>
        <p class="text-muted mb-0">
            Quickly generate and add multiple restaurant dining tables in bulk with automatic numbering and QR codes.
        </p>
    </div>

    <a href="{{ route('admin.tables.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i> Back to Tables
    </a>
</div>

{{-- Validation Errors --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following issues:</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.tables.extra.store') }}" method="POST" id="extraTablesForm">
                    @csrf

                    <h5 class="fw-bold mb-3 text-secondary border-bottom pb-2">
                        <i class="bi bi-123 me-2"></i>Numbering & Quantity
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Table Prefix <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="prefix"
                                   id="inputPrefix"
                                   class="form-control"
                                   value="{{ old('prefix', 'T') }}"
                                   placeholder="e.g. T, Table-, A"
                                   required>
                            <small class="text-muted">e.g. T, Table-, OUT</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Start Number <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="start_number"
                                   id="inputStartNumber"
                                   class="form-control"
                                   value="{{ old('start_number', $suggestedStart) }}"
                                   min="1"
                                   required>
                            <small class="text-muted">Starting integer</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Quantity / Count <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="count"
                                   id="inputCount"
                                   class="form-control"
                                   value="{{ old('count', 5) }}"
                                   min="1"
                                   max="50"
                                   required>
                            <small class="text-muted">How many tables</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Zero Padding</label>
                            <select name="pad_zeros" id="inputPadZeros" class="form-select">
                                <option value="2" @selected(old('pad_zeros', '2') == '2')>2 Digits (T01, T02)</option>
                                <option value="3" @selected(old('pad_zeros') == '3')>3 Digits (T001, T002)</option>
                                <option value="0" @selected(old('pad_zeros') == '0')>No Padding (T1, T2)</option>
                            </select>
                            <small class="text-muted">Format style</small>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 text-secondary border-bottom pb-2">
                        <i class="bi bi-sliders me-2"></i>Table Attributes & Location
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Default Capacity <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people"></i></span>
                                <input type="number"
                                       name="capacity"
                                       class="form-control"
                                       value="{{ old('capacity', 4) }}"
                                       min="1"
                                       max="100"
                                       required>
                            </div>
                            <small class="text-muted">Seats per table</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Table Type <span class="text-danger">*</span></label>
                            <select name="table_type" class="form-select" required>
                                <option value="regular" @selected(old('table_type') === 'regular')>Regular</option>
                                <option value="round" @selected(old('table_type') === 'round')>Round</option>
                                <option value="square" @selected(old('table_type') === 'square')>Square</option>
                                <option value="outdoor" @selected(old('table_type') === 'outdoor')>Outdoor</option>
                                <option value="private" @selected(old('table_type') === 'private')>Private Dining</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Area / Section</label>
                            <select name="area" id="selectArea" class="form-select mb-2">
                                <option value="">-- Choose Existing Area or Type New --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area }}" @selected(old('area') === $area)>{{ $area }}</option>
                                @endforeach
                            </select>
                            <input type="text"
                                   name="custom_area"
                                   id="inputCustomArea"
                                   class="form-control"
                                   value="{{ old('custom_area') }}"
                                   placeholder="Or enter new area name...">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Optional Notes / Tag</label>
                        <textarea name="notes"
                                  rows="2"
                                  class="form-control"
                                  placeholder="e.g. Near window, Garden side, Hall A">{{ old('notes') }}</textarea>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input"
                               type="checkbox"
                               name="is_active"
                               id="isActiveCheck"
                               value="1"
                               checked>
                        <label class="form-check-label fw-semibold" for="isActiveCheck">
                            Mark generated tables as Active & Ready for QR ordering
                        </label>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-danger px-4 py-2">
                            <i class="bi bi-plus-circle me-1"></i> Generate Extra Tables
                        </button>
                        <a href="{{ route('admin.tables.index') }}" class="btn btn-light px-4 py-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Live Preview Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="bg-danger-subtle text-danger p-2 rounded-3 me-3">
                        <i class="bi bi-eye fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Dynamic Preview</h5>
                        <small class="text-muted">Live preview of tables to be created</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="text-muted small">Total Tables:</span>
                    <span class="fw-bold fs-5 text-dark ms-1" id="previewCount">5</span>
                </div>

                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="small fw-semibold text-muted mb-2">Generated Table Numbers:</div>
                    <div class="d-flex flex-wrap gap-2" id="previewPills">
                        <!-- Filled by JS -->
                    </div>
                </div>

                <div class="alert alert-info py-2 px-3 small rounded-3 mb-0">
                    <i class="bi bi-qr-code me-1"></i>
                    Unique QR codes will be generated automatically for each table once saved!
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefixEl = document.getElementById('inputPrefix');
    const startEl = document.getElementById('inputStartNumber');
    const countEl = document.getElementById('inputCount');
    const padZerosEl = document.getElementById('inputPadZeros');
    const previewCountEl = document.getElementById('previewCount');
    const previewPillsEl = document.getElementById('previewPills');

    function updatePreview() {
        const prefix = prefixEl.value || 'T';
        const start = parseInt(startEl.value, 10) || 1;
        const count = Math.min(Math.max(parseInt(countEl.value, 10) || 1, 1), 50);
        const pad = parseInt(padZerosEl.value, 10) || 0;

        previewCountEl.textContent = count;
        previewPillsEl.innerHTML = '';

        for (let i = 0; i < count; i++) {
            const num = start + i;
            let strNum = String(num);
            if (pad > 0) {
                strNum = strNum.padStart(pad, '0');
            }
            const tableName = prefix + strNum;

            const pill = document.createElement('span');
            pill.className = 'badge bg-white text-dark border shadow-sm px-2 py-1';
            pill.innerHTML = `<i class="bi bi-table text-danger me-1"></i> ${tableName}`;
            previewPillsEl.appendChild(pill);
        }
    }

    prefixEl.addEventListener('input', updatePreview);
    startEl.addEventListener('input', updatePreview);
    countEl.addEventListener('input', updatePreview);
    padZerosEl.addEventListener('change', updatePreview);

    updatePreview();
});
</script>

@endsection
