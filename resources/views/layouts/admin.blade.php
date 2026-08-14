<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Dashboard') |
        Restaurant Admin
    </title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">


    <style>

        :root {
            --sidebar-width: 260px;
            --primary: #dc3545;
            --sidebar: #111827;
        }

        body {
            background: #f5f7fb;
            font-family:
                Inter,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        /* Sidebar */

        .sidebar {

            position: fixed;

            top: 0;
            left: 0;

            width: var(--sidebar-width);

            height: 100vh;

            background: var(--sidebar);

            color: #fff;

            z-index: 1000;

            overflow-y: auto;

        }

        .sidebar-brand {

            height: 75px;

            display: flex;

            align-items: center;

            padding: 0 24px;

            border-bottom:
                1px solid rgba(255,255,255,.08);

        }

        .brand-logo {

            width: 40px;
            height: 40px;

            border-radius: 12px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: var(--primary);

            margin-right: 12px;

        }

        .sidebar-menu {

            padding: 20px 12px;

        }

        .menu-title {

            color: #6b7280;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1px;

            padding: 10px 14px;

        }

        .sidebar .nav-link {

            color: #9ca3af;

            padding: 12px 14px;

            border-radius: 10px;

            margin-bottom: 4px;

            display: flex;

            align-items: center;

            transition: .2s;

        }

        .sidebar .nav-link i {

            width: 25px;

            font-size: 17px;

        }

        .sidebar .nav-link:hover {

            color: #fff;

            background: rgba(255,255,255,.07);

        }

        .sidebar .nav-link.active {

            background: var(--primary);

            color: #fff;

        }


        /* Main */

        .main {

            margin-left: var(--sidebar-width);

            min-height: 100vh;

        }


        /* Navbar */

        .topbar {

            height: 75px;

            background: #fff;

            border-bottom: 1px solid #e5e7eb;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 30px;

        }


        /* Content */

        .content {

            padding: 30px;

        }


        /* Cards */

        .stat-card {

            background: #fff;

            border: 0;

            border-radius: 16px;

            padding: 22px;

            box-shadow:
                0 5px 20px rgba(0,0,0,.04);

        }

        .stat-icon {

            width: 52px;
            height: 52px;

            border-radius: 14px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 22px;

        }


        /* Mobile */

        @media(max-width: 991px) {

            .sidebar {

                transform: translateX(-100%);

                transition: .3s;

            }

            .sidebar.show {

                transform: translateX(0);

            }

            .main {

                margin-left: 0;

            }

        }

    </style>

    @stack('styles')

</head>


<body>


<!-- Sidebar -->

<aside
    class="sidebar"
    id="sidebar">


    <!-- Brand -->

    <div class="sidebar-brand">

        <div class="brand-logo">

            <i class="bi bi-shop"></i>

        </div>

        <div>

            <div class="fw-bold">
                Restaurant
            </div>
        

            <small class="text-secondary">
                Admin Panel
            </small>

        </div>

    </div>


    <!-- Menu -->

    <div class="sidebar-menu">


        <div class="menu-title">
            Main
        </div>


        @can('dashboard.view')

            <a
                href="{{ route('admin.dashboard') }}"
                class="nav-link
                {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                <i class="bi bi-grid-1x2"></i>

                Dashboard

            </a>

        @endcan


        @can('orders.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-receipt"></i>

                Orders

            </a>

        @endcan


        @can('pos.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-display"></i>

                POS

            </a>

        @endcan


        @can('kitchen.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-fire"></i>

                Kitchen

            </a>

        @endcan


        <div class="menu-title mt-3">
            Restaurant
        </div>

@can('categories.view')

    <a
        href="{{ route('admin.categories.index') }}"
        class="nav-link
        {{ request()->routeIs('admin.categories.*')
            ? 'active'
            : '' }}">

        <i class="bi bi-tags"></i>

        Categories

    </a>

@endcan
        @can('menu.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-menu-button-wide"></i>

                Menu

            </a>

        @endcan


        @can('tables.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-grid-3x3-gap"></i>

                Tables

            </a>

        @endcan


        @can('reservations.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-calendar-check"></i>

                Reservations

            </a>

        @endcan


        @can('customers.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-people"></i>

                Customers

            </a>

        @endcan


        <div class="menu-title mt-3">
            Management
        </div>


        @can('payments.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-credit-card"></i>

                Payments

            </a>

        @endcan


        @can('reports.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-bar-chart"></i>

                Reports

            </a>

        @endcan


        @can('users.view')

            <a
                href="{{ route('admin.users.index') }}"
                class="nav-link
                {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                <i class="bi bi-person-gear"></i>

                Users

            </a>

        @endcan

        @can('roles.view')

            <a
                href="{{ route('admin.roles.index') }}"
                class="nav-link
                {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">

                <i class="bi bi-shield-check"></i>

                Roles

            </a>

        @endcan

        @can('roles.view')

            <a
                href="{{ route('admin.permissions.index') }}"
                class="nav-link
                {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">

                <i class="bi bi-key"></i>

                Permissions

            </a>

        @endcan


        @can('settings.view')

            <a
                href="#"
                class="nav-link">

                <i class="bi bi-gear"></i>

                Settings

            </a>

        @endcan

    </div>

</aside>


<!-- Main -->

<div class="main">


    <!-- Topbar -->

    <header class="topbar">


        <button
            class="btn btn-light d-lg-none"
            onclick="toggleSidebar()">

            <i class="bi bi-list fs-5"></i>

        </button>


        <div class="d-none d-lg-block">

            <h5 class="mb-0 fw-bold">

                @yield('page-title', 'Dashboard')

            </h5>

        </div>


        <div class="dropdown">

            <button
                class="btn btn-light dropdown-toggle"
                data-bs-toggle="dropdown">

                <i class="bi bi-person-circle me-1"></i>

                {{ auth()->user()->name }}

            </button>


            <ul class="dropdown-menu dropdown-menu-end">

                <li>

                    <span class="dropdown-item-text">

                        <strong>
                            {{ auth()->user()->name }}
                        </strong>

                        <br>

                        <small class="text-muted">

                            {{ auth()->user()->email }}

                        </small>

                    </span>

                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>

                    <form
                        method="POST"
                        action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
                            class="dropdown-item text-danger">

                            <i class="bi bi-box-arrow-right me-2"></i>

                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </header>


    <!-- Content -->

    <main class="content">

        @yield('content')

    </main>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

function toggleSidebar()
{
    document
        .getElementById('sidebar')
        .classList.toggle('show');
}

</script>


@stack('scripts')

</body>

</html>