<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Login | Restaurant Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <style>

        body {
            min-height: 100vh;
            background:
                linear-gradient(
                    135deg,
                    rgba(15, 23, 42, .95),
                    rgba(30, 41, 59, .92)
                ),
                url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4')
                center / cover;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(255,255,255,.97);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 70px rgba(0,0,0,.35);
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: #dc3545;
            color: #fff;
            font-size: 32px;
            margin: 0 auto 20px;
        }

        .form-control {
            height: 52px;
            border-radius: 12px;
            padding-left: 45px;
        }

        .input-group-custom {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            color: #6c757d;
        }

        .btn-login {
            height: 52px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
        }

        .brand-title {
            font-weight: 700;
            color: #212529;
        }

        .brand-subtitle {
            color: #6c757d;
        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <!-- Logo -->

        <div class="brand-icon">

            <i class="bi bi-shop"></i>

        </div>


        <!-- Heading -->

        <div class="text-center mb-4">

            <h3 class="brand-title mb-1">
                Restaurant Admin
            </h3>

            <p class="brand-subtitle mb-0">
                Sign in to manage your restaurant
            </p>

        </div>


        <!-- Validation Errors -->

        @if ($errors->any())

            <div class="alert alert-danger">

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ $errors->first() }}

            </div>

        @endif


        <!-- Login Form -->

        <form
            method="POST"
            action="{{ route('login') }}">

            @csrf


            <!-- Email -->

            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Email Address
                </label>

                <div class="input-group-custom">

                    <i class="bi bi-envelope input-icon"></i>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="admin@restaurant.com"
                        required
                        autofocus>

                </div>

                @error('email')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Password -->

            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Password
                </label>

                <div class="input-group-custom">

                    <i class="bi bi-lock input-icon"></i>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        required>

                </div>

                @error('password')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Remember -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember">

                    <label
                        class="form-check-label"
                        for="remember">

                        Remember me

                    </label>

                </div>


                @if (Route::has('password.request'))

                    <a
                        href="{{ route('password.request') }}"
                        class="text-decoration-none">

                        Forgot password?

                    </a>

                @endif

            </div>


            <!-- Submit -->

            <button
                type="submit"
                class="btn btn-danger btn-login w-100">

                <i class="bi bi-box-arrow-in-right me-2"></i>

                Sign In

            </button>

        </form>


        <div class="text-center mt-4">

            <small class="text-muted">

                Restaurant Management System

            </small>

        </div>

    </div>

</div>

</body>

</html>