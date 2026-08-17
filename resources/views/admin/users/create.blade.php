@extends('layouts.admin')

@section('title', 'Create User')

@section('page-title', 'Create User')


@section('content')

    <div class="row justify-content-center">

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-lg-5">

                    <div class="mb-4">

                        <h4 class="fw-bold">
                            Create User
                        </h4>

                        <p class="text-muted">
                            Add a new restaurant staff member.
                        </p>

                    </div>


                    <form method="POST" action="{{ route('admin.users.store') }}">

                        @csrf


                        <div class="row g-3">


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Full Name
                                </label>

                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>

                                @error('name')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Email
                                </label>

                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>

                                @error('email')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>
                            <div class="col-md-6">

                                <label for="aadhar_number" class="form-label fw-semibold">

                                    Aadhar Number

                                </label>

                                <input type="text" id="aadhar_number" name="aadhar_number"
                                    value="{{ old('aadhar_number') }}" class="form-control"
                                    placeholder="Enter Aadhar number" maxlength="20" autocomplete="off">

                                @error('aadhar_number')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label for="pan_number" class="form-label fw-semibold">
                                    PAN Number
                                </label>

                                <input type="text" id="pan_number" name="pan_number" value="{{ old('pan_number') }}"
                                    class="form-control text-uppercase" placeholder="Enter PAN number" maxlength="20"
                                    autocomplete="off">

                                @error('pan_number')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-12">

                                <label for="address" class="form-label fw-semibold">
                                    Address
                                </label>

                                <textarea id="address" name="address" rows="3" maxlength="1000" class="form-control"
                                    placeholder="Enter complete address">{{ old('address') }}</textarea>

                                @error('address')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Password
                                </label>

                                <input type="password" name="password" class="form-control" required>

                                @error('password')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Confirm Password
                                </label>

                                <input type="password" name="password_confirmation" class="form-control" required>

                            </div>


                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Role
                                </label>

                                <select name="role" class="form-select" required>

                                    <option value="">
                                        Select Role
                                    </option>

                                    @foreach($roles as $role)

                                        <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                            {{ $role->name }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('role')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                        </div>


                        <div class="mt-4">

                            <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-danger">

                                <i class="bi bi-check-lg me-2"></i>

                                Create User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection