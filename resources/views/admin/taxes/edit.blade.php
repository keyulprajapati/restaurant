@extends('layouts.admin')

@section('title', 'Edit Tax')

@section('page-title', 'Edit Tax')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-9">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-3"
                         style="width:52px;height:52px;">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">Edit Tax Rule</h4>
                        <p class="text-muted mb-0">Update tax details and GST breakdown for <strong>{{ $tax->name }}</strong></p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Please check the form errors below:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.taxes.update', $tax) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                Tax Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $tax->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   maxlength="100"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Code --}}
                        <div class="col-md-6">
                            <label for="code" class="form-label fw-semibold">
                                Tax Code <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="code"
                                   name="code"
                                   value="{{ old('code', $tax->code) }}"
                                   class="form-control @error('code') is-invalid @enderror"
                                   maxlength="50"
                                   required>
                            <div class="form-text">Letters, numbers, hyphens and underscores only.</div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div class="col-md-6">
                            <label for="type" class="form-label fw-semibold">
                                Tax Type <span class="text-danger">*</span>
                            </label>
                            <select id="type"
                                    name="type"
                                    class="form-select @error('type') is-invalid @enderror"
                                    required>
                                <option value="percentage" @selected(old('type', $tax->type) === 'percentage')>Percentage (%)</option>
                                <option value="fixed" @selected(old('type', $tax->type) === 'fixed')>Fixed Amount (₹)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Rate --}}
                        <div class="col-md-6">
                            <label for="rate" class="form-label fw-semibold">
                                Total Tax Rate / Amount <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       id="rate"
                                       name="rate"
                                       value="{{ old('rate', $tax->rate) }}"
                                       class="form-control @error('rate') is-invalid @enderror"
                                       min="0"
                                       step="0.01"
                                       required>
                                <span class="input-group-text fw-bold" id="rateSuffix">%</span>
                            </div>
                            <div class="form-text" id="rateHelp">Total tax rate percentage or amount.</div>
                            @error('rate')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Applies To --}}
                        <div class="col-md-6">
                            <label for="applies_to" class="form-label fw-semibold">
                                Applies To <span class="text-danger">*</span>
                            </label>
                            <select id="applies_to"
                                    name="applies_to"
                                    class="form-select @error('applies_to') is-invalid @enderror"
                                    required>
                                <option value="all" @selected(old('applies_to', $tax->applies_to) === 'all')>All Items & Services</option>
                                <option value="food" @selected(old('applies_to', $tax->applies_to) === 'food')>Food</option>
                                <option value="beverage" @selected(old('applies_to', $tax->applies_to) === 'beverage')>Beverage</option>
                                <option value="service" @selected(old('applies_to', $tax->applies_to) === 'service')>Service</option>
                            </select>
                            @error('applies_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Active Status --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-block">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       @checked(old('is_active', $tax->is_active))>
                                <label class="form-check-label fw-semibold" for="is_active">Active</label>
                            </div>
                            <small class="text-muted">Inactive taxes won't be applied to new orders.</small>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      maxlength="500"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Optional tax description...">{{ old('description', $tax->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- GST Breakdown / Variations Card --}}
                    <div id="gstSection" class="card border border-danger-subtle bg-light rounded-3 mt-4 mb-2">
                        <div class="card-header bg-white border-bottom border-light py-3 d-flex align-items-center">
                            <div class="rounded-circle bg-danger-subtle text-danger p-2 me-3 d-inline-flex">
                                <i class="bi bi-diagram-3-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">GST Breakdown & Variations</h6>
                                <small class="text-muted">Intra-State GST (CGST + SGST) and Inter-State GST (IGST) split rate configuration.</small>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                {{-- CGST Rate --}}
                                <div class="col-md-4">
                                    <label for="cgst_rate" class="form-label fw-semibold text-dark">
                                        CGST Rate (%) <span class="badge bg-primary-subtle text-primary ms-1">Central Tax</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               name="cgst_rate"
                                               id="cgst_rate"
                                               value="{{ old('cgst_rate', $tax->cgst_rate) }}"
                                               class="form-control @error('cgst_rate') is-invalid @enderror"
                                               placeholder="0.00">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('cgst_rate')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- SGST Rate --}}
                                <div class="col-md-4">
                                    <label for="sgst_rate" class="form-label fw-semibold text-dark">
                                        SGST Rate (%) <span class="badge bg-info-subtle text-info ms-1">State Tax</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               name="sgst_rate"
                                               id="sgst_rate"
                                               value="{{ old('sgst_rate', $tax->sgst_rate) }}"
                                               class="form-control @error('sgst_rate') is-invalid @enderror"
                                               placeholder="0.00">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('sgst_rate')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- IGST Rate --}}
                                <div class="col-md-4">
                                    <label for="igst_rate" class="form-label fw-semibold text-dark">
                                        IGST Rate (%) <span class="badge bg-secondary-subtle text-secondary ms-1">Integrated Tax</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               name="igst_rate"
                                               id="igst_rate"
                                               value="{{ old('igst_rate', $tax->igst_rate) }}"
                                               class="form-control @error('igst_rate') is-invalid @enderror"
                                               placeholder="0.00">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('igst_rate')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="alert alert-info border-0 shadow-sm mt-3 mb-0 d-flex align-items-center">
                                <i class="bi bi-info-circle-fill fs-5 me-2 flex-shrink-0"></i>
                                <div class="small">
                                    <strong>GST Division Rule:</strong> Modifying the total GST rate (e.g. <code>18%</code>) will re-calculate CGST (9%) + SGST (9%) and IGST (18%) unless manually overridden.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.taxes.index') }}" class="btn btn-light border px-4">Cancel</a>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
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
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const rateInput = document.getElementById('rate');
    const rateSuffix = document.getElementById('rateSuffix');
    const rateHelp = document.getElementById('rateHelp');
    const gstSection = document.getElementById('gstSection');

    const cgstInput = document.getElementById('cgst_rate');
    const sgstInput = document.getElementById('sgst_rate');
    const igstInput = document.getElementById('igst_rate');

    let userTouchedGst = false;

    [cgstInput, sgstInput, igstInput].forEach(el => {
        el.addEventListener('input', function() {
            userTouchedGst = true;
        });
    });

    function updateGSTDivision() {
        if (typeSelect.value !== 'percentage') {
            gstSection.style.display = 'none';
            return;
        }

        gstSection.style.display = 'block';

        if (!userTouchedGst) {
            const totalRate = parseFloat(rateInput.value) || 0;
            if (totalRate > 0) {
                const halfRate = (totalRate / 2).toFixed(2);
                cgstInput.value = halfRate;
                sgstInput.value = halfRate;
                igstInput.value = totalRate.toFixed(2);
            }
        }
    }

    function updateRateUI() {
        if (typeSelect.value === 'percentage') {
            rateSuffix.textContent = '%';
            rateHelp.textContent = 'Enter total GST percentage rate.';
            rateInput.max = '100';
            gstSection.style.display = 'block';
        } else if (typeSelect.value === 'fixed') {
            rateSuffix.textContent = '₹';
            rateHelp.textContent = 'Enter fixed tax amount in Rupee.';
            rateInput.max = '99999999.99';
            gstSection.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', function() {
        userTouchedGst = false;
        updateRateUI();
        updateGSTDivision();
    });

    rateInput.addEventListener('input', function() {
        updateGSTDivision();
    });

    updateRateUI();
});
</script>
@endpush