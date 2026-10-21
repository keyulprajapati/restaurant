<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Kot;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $allowedBranchIds = $user->allowedBranchIds();
        $branches = Branch::query()
            ->where('is_active', true)
            ->when($allowedBranchIds !== null, fn ($query) => $query->whereIn('id', $allowedBranchIds))
            ->orderBy('name')
            ->get();

        $selectedBranchId = $request->query('branch_id');
        if ($selectedBranchId && !$user->canAccessBranch((int) $selectedBranchId)) {
            abort(403);
        }

        $applyOrderScope = function ($query) use ($allowedBranchIds, $selectedBranchId) {
            if ($allowedBranchIds !== null) {
                $query->whereIn('branch_id', $allowedBranchIds);
            }

            if ($selectedBranchId) {
                $query->where('branch_id', $selectedBranchId);
            }

            return $query;
        };

        $today = now()->startOfDay();
        $yesterday = $today->copy()->subDay();
        $monthStart = now()->startOfMonth();
        $todayOrders = $applyOrderScope(Order::query())->whereDate('created_at', $today)->count();
        $yesterdayOrders = $applyOrderScope(Order::query())->whereDate('created_at', $yesterday)->count();
        $todaySales = $applyOrderScope(Order::query())
            ->whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('grand_total');
        $yesterdaySales = $applyOrderScope(Order::query())
            ->whereDate('created_at', $yesterday)
            ->where('status', '!=', 'cancelled')
            ->sum('grand_total');
        $monthlyCustomers = $applyOrderScope(Order::query())
            ->whereBetween('created_at', [$monthStart, now()])
            ->whereNotNull('customer_id')
            ->selectRaw('COUNT(DISTINCT customer_id) as aggregate')
            ->value('aggregate');

        $tableQuery = RestaurantTable::query()->where('is_active', true);
        if ($allowedBranchIds !== null) {
            $tableQuery->whereIn('branch_id', $allowedBranchIds);
        }
        if ($selectedBranchId) {
            $tableQuery->where('branch_id', $selectedBranchId);
        }
        $activeTables = (clone $tableQuery)->count();
        $availableTables = (clone $tableQuery)->where('status', 'available')->count();
        $occupiedTables = (clone $tableQuery)->where('status', 'occupied')->count();

        $weekStart = $today->copy()->subDays(6);
        $salesByDay = $applyOrderScope(Order::query())
            ->whereBetween('created_at', [$weekStart, now()])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as sale_date, SUM(grand_total) as total')
            ->groupBy('sale_date')
            ->pluck('total', 'sale_date');
        $weeklySales = collect(range(6, 0))->map(function ($daysAgo) use ($today, $salesByDay) {
            $day = $today->copy()->subDays($daysAgo);

            return [
                'label' => $day->format('D'),
                'date' => $day->format('d M'),
                'total' => (float) ($salesByDay[$day->toDateString()] ?? 0),
            ];
        });
        $maxDailySales = max(1, $weeklySales->max('total'));

        $recentOrders = $applyOrderScope(Order::with(['customer', 'table', 'branch']))
            ->latest()
            ->limit(6)
            ->get();

        $openKots = Kot::whereIn('status', ['pending', 'preparing'])
            ->whereHas('order', function ($query) use ($allowedBranchIds, $selectedBranchId) {
                if ($allowedBranchIds !== null) {
                    $query->whereIn('branch_id', $allowedBranchIds);
                }
                if ($selectedBranchId) {
                    $query->where('branch_id', $selectedBranchId);
                }
            })
            ->count();

        $reservationsQuery = Reservation::with(['customer', 'table', 'branch'])
            ->whereDate('reservation_date', '>=', $today)
            ->whereIn('status', ['pending', 'confirmed']);
        if ($allowedBranchIds !== null) {
            $reservationsQuery->whereIn('branch_id', $allowedBranchIds);
        }
        if ($selectedBranchId) {
            $reservationsQuery->where('branch_id', $selectedBranchId);
        }
        $upcomingReservations = $reservationsQuery
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->limit(5)
            ->get();

        $ordersChange = $this->percentageChange($todayOrders, $yesterdayOrders);
        $salesChange = $this->percentageChange((float) $todaySales, (float) $yesterdaySales);
        $selectedBranchName = $selectedBranchId
            ? $branches->firstWhere('id', (int) $selectedBranchId)?->name
            : null;

        return view('admin.dashboard', compact(
            'branches',
            'selectedBranchId',
            'selectedBranchName',
            'todayOrders',
            'yesterdayOrders',
            'todaySales',
            'monthlyCustomers',
            'activeTables',
            'availableTables',
            'occupiedTables',
            'weeklySales',
            'maxDailySales',
            'recentOrders',
            'openKots',
            'upcomingReservations',
            'ordersChange',
            'salesChange',
        ));
    }

    private function percentageChange(float|int $current, float|int $previous): ?float
    {
        if ($previous == 0) {
            return $current == 0 ? 0 : null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}