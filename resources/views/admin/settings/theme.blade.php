@extends('layouts.admin')

@section('title', 'Theme Settings')

@section('page-title', 'Theme Customizer')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Theme Customizer</h3>
        <p class="text-muted mb-0">Easily customize primary branding colors, background, sidebar, font colors, and typography across the entire site.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" onclick="applyBrahmaniPreset()">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset to Brahmani Theme
        </button>
    </div>
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Error Alert --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
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

    {{-- Left Navigation --}}
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 90px;">
            <div class="card-body p-3">
                <div class="nav flex-column nav-pills gap-1">
                    <a class="nav-link text-dark d-flex align-items-center gap-2 py-2 px-3 rounded-3" href="{{ route('admin.settings.general') }}">
                        <i class="bi bi-sliders fs-5"></i>
                        <span class="fw-semibold">General Settings</span>
                    </a>
                    <a class="nav-link active bg-danger d-flex align-items-center gap-2 py-2 px-3 rounded-3" href="{{ route('admin.settings.theme') }}">
                        <i class="bi bi-palette fs-5"></i>
                        <span class="fw-semibold">Theme Customizer</span>
                    </a>
                </div>
            </div>
            <div class="card-footer bg-light border-0 p-3 rounded-bottom-4">
                <small class="text-muted d-block">
                    <i class="bi bi-info-circle text-primary me-1"></i> Changes apply dynamically to all admin screens and user interfaces.
                </small>
            </div>
        </div>
    </div>

    {{-- Main Customizer Section --}}
    <div class="col-lg-9">

        {{-- 1. Featured Brahmani Logo Theme Banner --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden"
             style="background: linear-gradient(135deg, #111F15 0%, #23422A 55%, #C69A39 100%);">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-md-7">
                        <span class="badge bg-warning text-dark font-monospace mb-2 px-3 py-1 fw-bold">
                            <i class="bi bi-award-fill me-1"></i> BRAHMANI LOGO MATCH
                        </span>
                        <h3 class="fw-bold mb-2 text-white">Brahmani Lili Haldar Palette</h3>
                        <p class="text-white-50 mb-3 small">
                            Automatically matches your site theme to your brand logo: <strong>Deep Emerald Green (`#23422A`)</strong>, <strong>Antique Gold (`#C69A39`)</strong>, <strong>Royal Cream Canvas (`#F9F6EE`)</strong>, and <strong>Outfit Typography</strong>.
                        </p>
                        <button type="button"
                                class="btn btn-warning btn-lg px-4 fw-bold shadow-sm"
                                onclick="applyBrahmaniPreset()">
                            <i class="bi bi-magic me-2"></i> Apply 1-Click Brahmani Logo Theme
                        </button>
                    </div>
                    <div class="col-md-5">
                        <div class="p-3 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-20 shadow">
                            <div class="text-uppercase font-monospace text-warning small fw-bold mb-2">Palette Colors</div>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle d-inline-block shadow-sm" style="width: 22px; height: 22px; background: #23422A; border: 2px solid #fff;"></span>
                                        <span class="small fw-semibold">Emerald Green</span>
                                    </div>
                                    <code class="text-white small">#23422A</code>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle d-inline-block shadow-sm" style="width: 22px; height: 22px; background: #C69A39; border: 2px solid #fff;"></span>
                                        <span class="small fw-semibold">Warm Gold</span>
                                    </div>
                                    <code class="text-white small">#C69A39</code>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle d-inline-block shadow-sm" style="width: 22px; height: 22px; background: #F9F6EE; border: 2px solid #fff;"></span>
                                        <span class="small fw-semibold">Royal Cream</span>
                                    </div>
                                    <code class="text-white small">#F9F6EE</code>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle d-inline-block shadow-sm" style="width: 22px; height: 22px; background: #111F15; border: 2px solid #fff;"></span>
                                        <span class="small fw-semibold">Dark Charcoal</span>
                                    </div>
                                    <code class="text-white small">#111F15</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.theme.update') }}" id="themeForm">
            @csrf

            {{-- 2. Quick Theme Presets --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-danger-subtle text-danger p-2 d-inline-flex">
                            <i class="bi bi-grid-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Quick Presets</h5>
                            <small class="text-muted">Click any palette preset to quickly apply matching color schemes</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">

                        {{-- Brahmani Emerald Gold --}}
                        <div class="col-6 col-md-3">
                            <div class="border rounded-4 p-3 text-center preset-tile transition shadow-sm h-100"
                                 style="cursor: pointer; background: #fff;"
                                 onclick="applyBrahmaniPreset()">
                                <div class="d-flex justify-content-center gap-1 mb-2">
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#23422A;"></span>
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#C69A39;"></span>
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#F9F6EE; border:1px solid #ddd;"></span>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark small">Brahmani Theme</h6>
                                <small class="text-muted" style="font-size:0.75rem;">Logo Emerald & Gold</small>
                            </div>
                        </div>

                        {{-- Crimson Classic --}}
                        <div class="col-6 col-md-3">
                            <div class="border rounded-4 p-3 text-center preset-tile transition shadow-sm h-100"
                                 style="cursor: pointer; background: #fff;"
                                 onclick="applyPreset('#dc3545', '#ffc107', '#f5f7fb', '#111827', '#212529', 'Inter')">
                                <div class="d-flex justify-content-center gap-1 mb-2">
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#dc3545;"></span>
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#ffc107;"></span>
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#f5f7fb; border:1px solid #ddd;"></span>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark small">Crimson Red</h6>
                                <small class="text-muted" style="font-size:0.75rem;">Red & Dark Gray</small>
                            </div>
                        </div>

                        {{-- Sapphire Emerald --}}
                        <div class="col-6 col-md-3">
                            <div class="border rounded-4 p-3 text-center preset-tile transition shadow-sm h-100"
                                 style="cursor: pointer; background: #fff;"
                                 onclick="applyPreset('#3b82f6', '#10b981', '#f0f9ff', '#0f172a', '#1e293b', 'Plus Jakarta Sans')">
                                <div class="d-flex justify-content-center gap-1 mb-2">
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#3b82f6;"></span>
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#10b981;"></span>
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#f0f9ff; border:1px solid #ddd;"></span>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark small">Sapphire Blue</h6>
                                <small class="text-muted" style="font-size:0.75rem;">Modern Blue & Green</small>
                            </div>
                        </div>

                        {{-- Royal Purple --}}
                        <div class="col-6 col-md-3">
                            <div class="border rounded-4 p-3 text-center preset-tile transition shadow-sm h-100"
                                 style="cursor: pointer; background: #fff;"
                                 onclick="applyPreset('#7c3aed', '#f59e0b', '#faf5ff', '#1e1b4b', '#2e1065', 'Playfair Display')">
                                <div class="d-flex justify-content-center gap-1 mb-2">
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#7c3aed;"></span>
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#f59e0b;"></span>
                                    <span class="rounded-circle shadow-sm" style="width:22px; height:22px; background:#faf5ff; border:1px solid #ddd;"></span>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark small">Royal Purple</h6>
                                <small class="text-muted" style="font-size:0.75rem;">Purple & Amber Accent</small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 3. Color & Typography Parameters --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-2 d-inline-flex">
                        <i class="bi bi-palette2 fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Theme Parameters & Controls</h5>
                        <small class="text-muted">Adjust specific color pickers and typography family</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">

                        {{-- Primary Color --}}
                        <div class="col-md-6">
                            <label for="primary_color" class="form-label fw-semibold">
                                Primary Branding Color <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="color"
                                       id="primaryColorPicker"
                                       value="{{ old('primary_color', $theme['primary_color']) }}"
                                       class="form-control form-control-color border-end-0"
                                       title="Choose primary color"
                                       oninput="syncInput(this.value, 'primary_color')">
                                <input type="text"
                                       id="primary_color"
                                       name="primary_color"
                                       value="{{ old('primary_color', $theme['primary_color']) }}"
                                       class="form-control font-monospace @error('primary_color') is-invalid @enderror"
                                       placeholder="#23422A"
                                       required
                                       oninput="syncPicker(this.value, 'primaryColorPicker')">
                            </div>
                            <div class="form-text">Controls main action buttons, active sidebar links, primary badges & headers.</div>
                            @error('primary_color')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Secondary Color --}}
                        <div class="col-md-6">
                            <label for="secondary_color" class="form-label fw-semibold">
                                Secondary / Accent Color <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="color"
                                       id="secondaryColorPicker"
                                       value="{{ old('secondary_color', $theme['secondary_color']) }}"
                                       class="form-control form-control-color border-end-0"
                                       title="Choose secondary color"
                                       oninput="syncInput(this.value, 'secondary_color')">
                                <input type="text"
                                       id="secondary_color"
                                       name="secondary_color"
                                       value="{{ old('secondary_color', $theme['secondary_color']) }}"
                                       class="form-control font-monospace @error('secondary_color') is-invalid @enderror"
                                       placeholder="#C69A39"
                                       required
                                       oninput="syncPicker(this.value, 'secondaryColorPicker')">
                            </div>
                            <div class="form-text">Controls gold accents, highlight tags, secondary buttons, and sub-titles.</div>
                            @error('secondary_color')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Background Color --}}
                        <div class="col-md-6">
                            <label for="background_color" class="form-label fw-semibold">
                                Main Canvas Background <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="color"
                                       id="backgroundColorPicker"
                                       value="{{ old('background_color', $theme['background_color']) }}"
                                       class="form-control form-control-color border-end-0"
                                       title="Choose background color"
                                       oninput="syncInput(this.value, 'background_color')">
                                <input type="text"
                                       id="background_color"
                                       name="background_color"
                                       value="{{ old('background_color', $theme['background_color']) }}"
                                       class="form-control font-monospace @error('background_color') is-invalid @enderror"
                                       placeholder="#F9F6EE"
                                       required
                                       oninput="syncPicker(this.value, 'backgroundColorPicker')">
                            </div>
                            <div class="form-text">Controls global page canvas background behind content cards.</div>
                            @error('background_color')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sidebar Color --}}
                        <div class="col-md-6">
                            <label for="sidebar_color" class="form-label fw-semibold">
                                Navigation Sidebar Color <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="color"
                                       id="sidebarColorPicker"
                                       value="{{ old('sidebar_color', $theme['sidebar_color']) }}"
                                       class="form-control form-control-color border-end-0"
                                       title="Choose sidebar color"
                                       oninput="syncInput(this.value, 'sidebar_color')">
                                <input type="text"
                                       id="sidebar_color"
                                       name="sidebar_color"
                                       value="{{ old('sidebar_color', $theme['sidebar_color']) }}"
                                       class="form-control font-monospace @error('sidebar_color') is-invalid @enderror"
                                       placeholder="#111F15"
                                       required
                                       oninput="syncPicker(this.value, 'sidebarColorPicker')">
                            </div>
                            <div class="form-text">Controls background color of the left navigation sidebar.</div>
                            @error('sidebar_color')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Font Color --}}
                        <div class="col-md-6">
                            <label for="font_color" class="form-label fw-semibold">
                                Base Text / Font Color <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="color"
                                       id="fontColorPicker"
                                       value="{{ old('font_color', $theme['font_color']) }}"
                                       class="form-control form-control-color border-end-0"
                                       title="Choose font color"
                                       oninput="syncInput(this.value, 'font_color')">
                                <input type="text"
                                       id="font_color"
                                       name="font_color"
                                       value="{{ old('font_color', $theme['font_color']) }}"
                                       class="form-control font-monospace @error('font_color') is-invalid @enderror"
                                       placeholder="#1C2E20"
                                       required
                                       oninput="syncPicker(this.value, 'fontColorPicker')">
                            </div>
                            <div class="form-text">Controls default body text, heading typography, and paragraph colors.</div>
                            @error('font_color')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Font Family --}}
                        <div class="col-md-6">
                            <label for="font_family" class="form-label fw-semibold">
                                Typography Font Family <span class="text-danger">*</span>
                            </label>
                            <select id="font_family"
                                    name="font_family"
                                    class="form-select @error('font_family') is-invalid @enderror"
                                    onchange="updateLivePreview()"
                                    required>
                                <option value="Outfit" @selected(old('font_family', $theme['font_family']) === 'Outfit')>Outfit (Modern Premium - Logo Match)</option>
                                <option value="Inter" @selected(old('font_family', $theme['font_family']) === 'Inter')>Inter (Clean System UI)</option>
                                <option value="Playfair Display" @selected(old('font_family', $theme['font_family']) === 'Playfair Display')>Playfair Display (Luxury Elegant Serif)</option>
                                <option value="Plus Jakarta Sans" @selected(old('font_family', $theme['font_family']) === 'Plus Jakarta Sans')>Plus Jakarta Sans (Modern Geometric)</option>
                                <option value="Roboto" @selected(old('font_family', $theme['font_family']) === 'Roboto')>Roboto (Standard Clean)</option>
                                <option value="Montserrat" @selected(old('font_family', $theme['font_family']) === 'Montserrat')>Montserrat (Classic Bold)</option>
                                <option value="Cinzel" @selected(old('font_family', $theme['font_family']) === 'Cinzel')>Cinzel (Royal Vintage Serif)</option>
                            </select>
                            <div class="form-text">Sets global font family dynamically across all views and layouts.</div>
                            @error('font_family')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- 4. Interactive Live Sandbox Preview --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex align-items-center gap-2">
                    <i class="bi bi-display-fill text-danger fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Live Real-Time UI Preview</h6>
                </div>
                <div class="card-body p-4" id="livePreviewBox" style="transition: all 0.3s ease;">

                    {{-- Top Navbar Mockup --}}
                    <div class="p-3 mb-4 rounded-3 d-flex align-items-center justify-content-between shadow-sm bg-white border">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark font-monospace">LOGO</span>
                            <span id="previewPageTitle" class="fw-bold fs-6">Sample Module Screen</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark border">Admin User</span>
                        </div>
                    </div>

                    <div class="row g-4 align-items-center">

                        {{-- Preview Buttons & Badges --}}
                        <div class="col-md-7">
                            <h5 id="previewHeading" class="fw-bold mb-2">Brahmani Lili Haldar - Live Interface Sandbox</h5>
                            <p id="previewText" class="small mb-3">
                                Adjust any color picker or preset above to see your site buttons, badges, background canvas, and fonts update live in real-time.
                            </p>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <button type="button" id="previewPrimaryBtn" class="btn text-white px-3 py-2 fw-semibold shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Primary Button
                                </button>

                                <button type="button" id="previewSecondaryBtn" class="btn text-dark px-3 py-2 fw-semibold shadow-sm">
                                    <i class="bi bi-star-fill me-1"></i> Accent Button
                                </button>

                                <span id="previewPrimaryBadge" class="badge px-3 py-2 rounded-pill fw-semibold">
                                    Primary Tag
                                </span>

                                <span id="previewSecondaryBadge" class="badge px-3 py-2 rounded-pill fw-semibold">
                                    Gold Accent Tag
                                </span>
                            </div>
                        </div>

                        {{-- Preview Sidebar Widget --}}
                        <div class="col-md-5">
                            <div id="previewSidebar" class="p-3 rounded-4 text-white shadow-sm" style="min-height: 140px;">
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-white border-opacity-10">
                                    <div id="previewBrandLogo" class="rounded-3 p-2 d-flex align-items-center justify-content-center text-white" style="width:34px; height:34px;">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    <span class="fw-bold text-white">Brahmani Admin</span>
                                </div>
                                <div id="previewActiveItem" class="p-2 rounded-3 text-white small fw-bold d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-grid-1x2 me-2"></i> Active Menu Item</span>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Submit Controls --}}
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.settings.general') }}" class="btn btn-light border px-4">Cancel</a>
                <button type="submit" class="btn btn-danger btn-lg px-5 shadow-sm rounded-3">
                    <i class="bi bi-check-circle-fill me-2"></i> Save Theme Settings
                </button>
            </div>

        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
function syncInput(colorValue, targetInputId) {
    if (!colorValue.startsWith('#')) {
        colorValue = '#' + colorValue;
    }
    document.getElementById(targetInputId).value = colorValue;
    updateLivePreview();
}

function syncPicker(textValue, targetPickerId) {
    if (!textValue.startsWith('#')) {
        textValue = '#' + textValue;
        document.getElementById(targetPickerId.replace('Picker', '')).value = textValue;
    }
    if (/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/.test(textValue)) {
        document.getElementById(targetPickerId).value = textValue;
        updateLivePreview();
    }
}

function applyPreset(primary, secondary, bg, sidebar, fontColor, fontFamily) {
    document.getElementById('primary_color').value = primary;
    document.getElementById('primaryColorPicker').value = primary;

    document.getElementById('secondary_color').value = secondary;
    document.getElementById('secondaryColorPicker').value = secondary;

    document.getElementById('background_color').value = bg;
    document.getElementById('backgroundColorPicker').value = bg;

    document.getElementById('sidebar_color').value = sidebar;
    document.getElementById('sidebarColorPicker').value = sidebar;

    document.getElementById('font_color').value = fontColor;
    document.getElementById('fontColorPicker').value = fontColor;

    document.getElementById('font_family').value = fontFamily;

    updateLivePreview();
}

function applyBrahmaniPreset() {
    applyPreset('#23422A', '#C69A39', '#F9F6EE', '#111F15', '#1C2E20', 'Outfit');
}

function updateLivePreview() {
    let primary = document.getElementById('primary_color').value || '#23422A';
    let secondary = document.getElementById('secondary_color').value || '#C69A39';
    let bg = document.getElementById('background_color').value || '#F9F6EE';
    let sidebar = document.getElementById('sidebar_color').value || '#111F15';
    let fontColor = document.getElementById('font_color').value || '#1C2E20';
    let fontFamily = document.getElementById('font_family').value || 'Outfit';

    if (!primary.startsWith('#')) primary = '#' + primary;
    if (!secondary.startsWith('#')) secondary = '#' + secondary;
    if (!bg.startsWith('#')) bg = '#' + bg;
    if (!sidebar.startsWith('#')) sidebar = '#' + sidebar;
    if (!fontColor.startsWith('#')) fontColor = '#' + fontColor;

    const liveBox = document.getElementById('livePreviewBox');
    if (liveBox) {
        liveBox.style.backgroundColor = bg;
        liveBox.style.color = fontColor;
        liveBox.style.fontFamily = fontFamily;
    }

    const primaryBtn = document.getElementById('previewPrimaryBtn');
    if (primaryBtn) {
        primaryBtn.style.backgroundColor = primary;
        primaryBtn.style.borderColor = primary;
    }

    const secondaryBtn = document.getElementById('previewSecondaryBtn');
    if (secondaryBtn) {
        secondaryBtn.style.backgroundColor = secondary;
        secondaryBtn.style.borderColor = secondary;
    }

    const primaryBadge = document.getElementById('previewPrimaryBadge');
    if (primaryBadge) {
        primaryBadge.style.backgroundColor = primary;
        primaryBadge.style.color = '#ffffff';
    }

    const secondaryBadge = document.getElementById('previewSecondaryBadge');
    if (secondaryBadge) {
        secondaryBadge.style.backgroundColor = secondary;
        secondaryBadge.style.color = '#111111';
    }

    const previewSidebar = document.getElementById('previewSidebar');
    if (previewSidebar) {
        previewSidebar.style.backgroundColor = sidebar;
    }

    const brandLogo = document.getElementById('previewBrandLogo');
    if (brandLogo) {
        brandLogo.style.backgroundColor = primary;
    }

    const activeItem = document.getElementById('previewActiveItem');
    if (activeItem) {
        activeItem.style.backgroundColor = primary;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateLivePreview();
});
</script>
@endpush
