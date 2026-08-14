@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')


@section('content')


<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Good evening, {{ auth()->user()->name }}
        </h3>

        <p class="text-muted mb-0">
            Here's what's happening with your restaurant today.
        </p>

    </div>

    @can('orders.create')

        <button class="btn btn-danger px-4">

            <i class="bi bi-plus-lg me-2"></i>

            New Order

        </button>

    @endcan

</div>


<!-- Statistics -->

<div class="row g-4 mb-4">


    <!-- Orders -->

    <div class="col-xl-3 col-md-6">

        <div class="stat-card">

            <div class="d-flex justify-content-between">

                <div>

                    <div class="text-muted small mb-2">
                        Today's Orders
                    </div>

                    <h3 class="fw-bold mb-1">
                        128
                    </h3>

                    <small class="text-success">

                        <i class="bi bi-arrow-up"></i>

                        12.5%

                    </small>

                    <span class="text-muted small">
                        vs yesterday
                    </span>

                </div>


                <div class="stat-icon bg-danger-subtle text-danger">

                    <i class="bi bi-receipt"></i>

                </div>

            </div>

        </div>

    </div>


    <!-- Revenue -->

    <div class="col-xl-3 col-md-6">

        <div class="stat-card">

            <div class="d-flex justify-content-between">

                <div>

                    <div class="text-muted small mb-2">
                        Today's Revenue
                    </div>

                    <h3 class="fw-bold mb-1">
                        $4,286
                    </h3>

                    <small class="text-success">

                        <i class="bi bi-arrow-up"></i>

                        8.4%

                    </small>

                    <span class="text-muted small">
                        vs yesterday
                    </span>

                </div>


                <div class="stat-icon bg-success-subtle text-success">

                    <i class="bi bi-currency-dollar"></i>

                </div>

            </div>

        </div>

    </div>


    <!-- Customers -->

    <div class="col-xl-3 col-md-6">

        <div class="stat-card">

            <div class="d-flex justify-content-between">

                <div>

                    <div class="text-muted small mb-2">
                        Customers
                    </div>

                    <h3 class="fw-bold mb-1">
                        856
                    </h3>

                    <small class="text-success">

                        <i class="bi bi-arrow-up"></i>

                        5.2%

                    </small>

                    <span class="text-muted small">
                        this month
                    </span>

                </div>


                <div class="stat-icon bg-primary-subtle text-primary">

                    <i class="bi bi-people"></i>

                </div>

            </div>

        </div>

    </div>


    <!-- Tables -->

    <div class="col-xl-3 col-md-6">

        <div class="stat-card">

            <div class="d-flex justify-content-between">

                <div>

                    <div class="text-muted small mb-2">
                        Available Tables
                    </div>

                    <h3 class="fw-bold mb-1">
                        12 / 24
                    </h3>

                    <small class="text-warning">

                        50% occupied

                    </small>

                </div>


                <div class="stat-icon bg-warning-subtle text-warning">

                    <i class="bi bi-grid-3x3-gap"></i>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- Main Dashboard -->

<div class="row g-4">


    <!-- Recent Orders -->

    <div class="col-xl-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">


                <div class="d-flex justify-content-between mb-4">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Recent Orders
                        </h5>

                        <p class="text-muted small mb-0">
                            Latest restaurant orders
                        </p>

                    </div>

                    @can('orders.view')

                        <a
                            href="#"
                            class="btn btn-sm btn-light">

                            View All

                        </a>

                    @endcan

                </div>


                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                        <tr>

                            <th>
                                Order
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Table
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                        </thead>


                        <tbody>


                        <tr>

                            <td>

                                <strong>
                                    #ORD-1028
                                </strong>

                                <br>

                                <small class="text-muted">
                                    5 min ago
                                </small>

                            </td>

                            <td>
                                John Smith
                            </td>

                            <td>
                                T-12
                            </td>

                            <td>
                                <strong>
                                    $86.50
                                </strong>
                            </td>

                            <td>

                                <span
                                    class="badge text-bg-success">

                                    Completed

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <strong>
                                    #ORD-1027
                                </strong>

                                <br>

                                <small class="text-muted">
                                    12 min ago
                                </small>

                            </td>

                            <td>
                                Sarah Wilson
                            </td>

                            <td>
                                T-08
                            </td>

                            <td>
                                <strong>
                                    $124.00
                                </strong>
                            </td>

                            <td>

                                <span
                                    class="badge text-bg-warning">

                                    Preparing

                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <strong>
                                    #ORD-1026
                                </strong>

                                <br>

                                <small class="text-muted">
                                    18 min ago
                                </small>

                            </td>

                            <td>
                                Michael Brown
                            </td>

                            <td>
                                T-03
                            </td>

                            <td>
                                <strong>
                                    $52.75
                                </strong>

                            </td>

                            <td>

                                <span
                                    class="badge text-bg-info">

                                    New

                                </span>

                            </td>

                        </tr>


                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <!-- Quick Actions -->

    <div class="col-xl-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-4">
                    Quick Actions
                </h5>


                @can('orders.create')

                    <button
                        class="btn btn-danger w-100 mb-3 py-2">

                        <i class="bi bi-plus-circle me-2"></i>

                        Create Order

                    </button>

                @endcan


                @can('menu.create')

                    <button
                        class="btn btn-light w-100 mb-3 py-2">

                        <i class="bi bi-plus-circle me-2"></i>

                        Add Menu Item

                    </button>

                @endcan


                @can('reservations.create')

                    <button
                        class="btn btn-light w-100 mb-3 py-2">

                        <i class="bi bi-calendar-plus me-2"></i>

                        New Reservation

                    </button>

                @endcan


                @can('reports.view')

                    <button
                        class="btn btn-light w-100 py-2">

                        <i class="bi bi-bar-chart me-2"></i>

                        View Reports

                    </button>

                @endcan

            </div>

        </div>


        <!-- Restaurant Status -->

        <div class="card border-0 shadow-sm rounded-4 mt-4">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="fw-bold">
                            Restaurant Status
                        </h6>

                        <small class="text-muted">
                            Current operating status
                        </small>

                    </div>


                    <span
                        class="badge text-bg-success">

                        <i class="bi bi-circle-fill me-1"
                           style="font-size:7px"></i>

                        Open

                    </span>

                </div>

                <hr>

                <div class="d-flex justify-content-between">

                    <span class="text-muted">
                        Today's Hours
                    </span>

                    <strong>
                        11:00 AM - 11:00 PM
                    </strong>

                </div>

            </div>

        </div>

    </div>

</div>


@endsection