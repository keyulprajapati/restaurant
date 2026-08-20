@extends('layouts.admin')

@section('title', 'General Settings')

@section('page-title', 'General Settings')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">General Settings</h3>
        <p class="text-muted mb-0">Manage global branding, site title, brand logo, favicon, and sub-parts site icon.</p>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Error Message --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        <strong>Please check the errors below:</strong>
        <ul class="mb-0 mt-2 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Settings Navigation / Info Card --}}
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 90px;">
            <div class="card-body p-3">
                <div class="nav flex-column nav-pills gap-1">
                    <a class="nav-link active bg-danger d-flex align-items-center gap-2 py-2 px-3 rounded-3" href="{{ route('admin.settings.general') }}">
                        <i class="bi bi-sliders fs-5"></i>
                        <span class="fw-semibold">General Settings</span>
                    </a>
                    <a class="nav-link text-dark d-flex align-items-center gap-2 py-2 px-3 rounded-3" href="{{ route('admin.settings.theme') }}">
                        <i class="bi bi-palette fs-5"></i>
                        <span class="fw-semibold">Theme Settings</span>
                    </a>
                </div>
            </div>
            <div class="card-footer bg-light border-0 p-3 rounded-bottom-4">
                <small class="text-muted d-block">
                    <i class="bi bi-shield-check text-success me-1"></i> Changes apply dynamically across all admin and public pages.
                </small>
            </div>
        </div>
    </div>

    {{-- Main Settings Form --}}
    <div class="col-lg-9">
        <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- 1. Site Identity --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-danger-subtle text-danger p-2 d-inline-flex">
                        <i class="bi bi-globe fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Site Identity</h5>
                        <small class="text-muted">Configure site name and main title representation</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="site_name" class="form-label fw-semibold">
                                Site Name / System Title <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="site_name"
                                   name="site_name"
                                   value="{{ old('site_name', $settings['site_name']) }}"
                                   class="form-control form-control-lg @error('site_name') is-invalid @enderror"
                                   placeholder="e.g. Royal Feast Restaurant"
                                   required>
                            <div class="form-text">Displayed in browser tab titles, header, sidebar brand, and email notifications.</div>
                            @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Branding Images & Icons --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-2 d-inline-flex">
                        <i class="bi bi-images fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Logos & Icons</h5>
                        <small class="text-muted">Upload brand logo, browser favicon, and sub-part site icon</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">

                        {{-- Brand Logo --}}
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 text-center bg-light h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <label class="form-label fw-semibold text-dark d-block mb-2">
                                        <i class="bi bi-shop me-1 text-danger"></i> Main Brand Logo
                                    </label>
                                    <div class="preview-container my-3 mx-auto rounded-3 d-flex align-items-center justify-content-center bg-white border shadow-sm p-2"
                                         style="width: 110px; height: 110px; overflow: hidden;">
                                        @if($settings['brand_logo'])
                                            <img id="brandLogoPreview" src="{{ asset($settings['brand_logo']) }}" alt="Brand Logo" class="img-fluid max-h-100">
                                        @else
                                            <div id="brandLogoFallback" class="text-secondary text-center">
                                                <i class="bi bi-shop fs-1 d-block text-danger"></i>
                                                <span class="small text-muted">No Logo</span>
                                            </div>
                                            <img id="brandLogoPreview" src="" alt="Brand Logo" class="img-fluid max-h-100 d-none">
                                        @endif
                                    </div>
                                    <small class="text-muted d-block mb-3">Appears in main sidebar header, navbar & login page. (PNG, JPG, SVG, WebP. Max: 2MB)</small>
                                </div>
                                <div>
                                    <input type="file"
                                           id="brand_logo"
                                           name="brand_logo"
                                           accept="image/*"
                                           class="form-control form-control-sm @error('brand_logo') is-invalid @enderror"
                                           onchange="previewImage(this, 'brandLogoPreview', 'brandLogoFallback')">
                                    @error('brand_logo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Favicon Icon --}}
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 text-center bg-light h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <label class="form-label fw-semibold text-dark d-block mb-2">
                                        <i class="bi bi-window me-1 text-primary"></i> Favicon Icon
                                    </label>
                                    <div class="preview-container my-3 mx-auto rounded-3 d-flex align-items-center justify-content-center bg-white border shadow-sm p-2"
                                         style="width: 110px; height: 110px; overflow: hidden;">
                                        @if($settings['favicon_icon'])
                                            <img id="faviconPreview" src="{{ asset($settings['favicon_icon']) }}" alt="Favicon" class="img-fluid max-h-100" style="max-height: 64px;">
                                        @else
                                            <div id="faviconFallback" class="text-secondary text-center">
                                                <i class="bi bi-browser-chrome fs-1 d-block text-primary"></i>
                                                <span class="small text-muted">No Favicon</span>
                                            </div>
                                            <img id="faviconPreview" src="" alt="Favicon" class="img-fluid max-h-100 d-none" style="max-height: 64px;">
                                        @endif
                                    </div>
                                    <small class="text-muted d-block mb-3">Appears in browser tabs and bookmarks bar. (.ICO, PNG, SVG. Max: 1MB)</small>
                                </div>
                                <div>
                                    <input type="file"
                                           id="favicon_icon"
                                           name="favicon_icon"
                                           accept=".ico,image/png,image/svg+xml,image/x-icon,image/jpeg"
                                           class="form-control form-control-sm @error('favicon_icon') is-invalid @enderror"
                                           onchange="previewImage(this, 'faviconPreview', 'faviconFallback')">
                                    @error('favicon_icon')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sub-parts Site Icon --}}
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 text-center bg-light h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <label class="form-label fw-semibold text-dark d-block mb-2">
                                        <i class="bi bi-app-indicator me-1 text-info"></i> Sub-parts Site Icon
                                    </label>
                                    <div class="preview-container my-3 mx-auto rounded-3 d-flex align-items-center justify-content-center bg-white border shadow-sm p-2"
                                         style="width: 110px; height: 110px; overflow: hidden;">
                                        @if($settings['site_icon'])
                                            <img id="siteIconPreview" src="{{ asset($settings['site_icon']) }}" alt="Site Icon" class="img-fluid max-h-100">
                                        @else
                                            <div id="siteIconFallback" class="text-secondary text-center">
                                                <i class="bi bi-tag-fill fs-1 d-block text-info"></i>
                                                <span class="small text-muted">No Sub-part Icon</span>
                                            </div>
                                            <img id="siteIconPreview" src="" alt="Site Icon" class="img-fluid max-h-100 d-none">
                                        @endif
                                    </div>
                                    <small class="text-muted d-block mb-3">Used across all sub-parts/sub-sections of the site (excludes main brand logo header). (PNG, SVG, WebP. Max: 2MB)</small>
                                </div>
                                <div>
                                    <input type="file"
                                           id="site_icon"
                                           name="site_icon"
                                           accept="image/*"
                                           class="form-control form-control-sm @error('site_icon') is-invalid @enderror"
                                           onchange="previewImage(this, 'siteIconPreview', 'siteIconFallback')">
                                    @error('site_icon')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 3. Live Preview & Sub-part Demonstration --}}
            <div class="card border border-info-subtle bg-light rounded-4 mb-4">
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center gap-2">
                    <i class="bi bi-eye-fill text-info fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Sub-part Site Icon Live Demonstration</h6>
                </div>
                <div class="card-body p-4">
                    <p class="small text-muted mb-3">
                        The <strong>Sub-parts Site Icon</strong> is automatically rendered alongside sub-module titles, section banners, and sub-pages to give a consistent, custom branded experience throughout all sub-parts of the system:
                    </p>
                    <div class="p-3 bg-white border rounded-3 d-flex align-items-center justify-content-between shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center p-2" style="width: 44px; height: 44px; overflow: hidden;">
                                @if($settings['site_icon'])
                                    <img src="{{ asset($settings['site_icon']) }}" alt="Sub-part Icon" class="img-fluid">
                                @else
                                    <i class="bi bi-award-fill fs-5"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Sample Sub-part Module Banner</h6>
                                <small class="text-muted">Demonstrates sub-section header styling with active Sub-part Site Icon</small>
                            </div>
                        </div>
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill">Sub-part Active</span>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-danger btn-lg px-5 shadow-sm rounded-3">
                    <i class="bi bi-check-lg me-2"></i> Save General Settings
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewImage(input, previewId, fallbackId) {
    const preview = document.getElementById(previewId);
    const fallback = document.getElementById(fallbackId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (fallback) {
                fallback.classList.add('d-none');
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
