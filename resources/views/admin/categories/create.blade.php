@extends('layouts.admin')

@section('title', 'Create Category')

@section('page-title', 'Create Category')


@section('content')

<div class="row justify-content-center">

    <div class="col-xl-7">

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
                        style="width:50px;height:50px;">

                        <i class="bi bi-tags fs-4"></i>

                    </div>


                    <div>

                        <h4 class="fw-bold mb-1">
                            Create Category
                        </h4>

                        <p class="text-muted mb-0">
                            Add a new menu category.
                        </p>

                    </div>

                </div>


                @if($errors->any())

                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('admin.categories.store') }}">

                    @csrf


                    {{-- Category Name --}}

                    <div class="mb-4">

                        <label
                            for="name"
                            class="form-label fw-semibold">

                            Category Name
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control form-control-lg
                                   @error('name') is-invalid @enderror"
                            placeholder="e.g. Main Course"
                            maxlength="100"
                            required
                            autofocus>

                        @error('name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}

                    <div class="mb-4">

                        <label
                            for="status"
                            class="form-label fw-semibold">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select form-select-lg
                                   @error('status') is-invalid @enderror"
                            required>

                            <option value="">
                                Select Status
                            </option>

                            <option
                                value="active"
                                @selected(old('status', 'active') === 'active')>

                                Active

                            </option>

                            <option
                                value="inactive"
                                @selected(old('status') === 'inactive')>

                                Inactive

                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Actions --}}

                    <div class="d-flex gap-2">

                        <a
                            href="{{ route('admin.categories.index') }}"
                            class="btn btn-light px-4">

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="btn btn-danger px-4">

                            <i class="bi bi-check-lg me-2"></i>

                            Create Category

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection