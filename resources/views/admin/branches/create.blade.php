@extends('layouts.admin')

@section('title', 'Create Branch')
@section('page-title', 'Create Branch')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5">
                <div class="mb-4">
                    <h4 class="fw-bold">Create Branch</h4>
                    <p class="text-muted mb-0">Set up a new restaurant branch for separate operations.</p>
                </div>

                <form method="POST" action="{{ route('admin.branches.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" class="form-control" required>
                            @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manager Name</label>
                            <input type="text" name="manager_name" value="{{ old('manager_name') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" @selected(old('is_active', true) == 1)>Active</option>
                                <option value="0" @selected(old('is_active') == 0)>Inactive</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" rows="3" class="form-control" placeholder="Branch address...">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.branches.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-danger">Save Branch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
