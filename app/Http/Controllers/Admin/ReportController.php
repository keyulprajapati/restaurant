<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function payments(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
            'payment_method' => ['nullable', 'in:cash,upi,card,other'],
        ]);

        $startDate = $validated['start_date'] ?? now()->toDateString();
        $endDate = $validated['end_date'] ?? now()->toDateString();
        $paymentMethod = $validated['payment_method'] ?? '';
        $allowedBranchIds = $request->user()->allowedBranchIds();

        $branchNames = Branch::query()
            ->when(
                $allowedBranchIds !== null,
                fn ($query) => $query->whereIn('id', $allowedBranchIds)
            )
            ->pluck('name', 'id');

        $payments = Order::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('payment_method')
            ->where('payment_method', '!=', '')
            ->when(
                $paymentMethod !== '',
                fn ($query) => $query->where('payment_method', $paymentMethod)
            )
            ->when(
                $allowedBranchIds !== null,
                fn ($query) => $query->whereIn('branch_id', $allowedBranchIds)
            )
            ->selectRaw(
                'branch_id, payment_method, COUNT(*) as order_count, ' .
                'SUM(grand_total) as amount'
            )
            ->groupBy('branch_id', 'payment_method')
            ->orderBy('branch_id')
            ->orderBy('payment_method')
            ->get()
            ->map(function ($payment) use ($branchNames) {
                $payment->branch_name = $payment->branch_id
                    ? ($branchNames[$payment->branch_id] ?? 'Unassigned Branch')
                    : 'Unassigned Branch';
                $payment->order_count = (int) $payment->order_count;
                $payment->amount = (float) $payment->amount;

                return $payment;
            });

        $summary = [
            'order_count' => $payments->sum('order_count'),
            'amount' => $payments->sum('amount'),
        ];

        return view('admin.reports.payments', compact(
            'payments',
            'summary',
            'startDate',
            'endDate',
            'paymentMethod',
        ));
    }

    public function sales(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
        ]);

        $startDate = $validated['start_date'] ?? now()->toDateString();
        $endDate = $validated['end_date'] ?? now()->toDateString();
        $allowedBranchIds = $request->user()->allowedBranchIds();

        $branches = Branch::query()
            ->when(
                $allowedBranchIds !== null,
                fn ($query) => $query->whereIn('id', $allowedBranchIds)
            )
            ->orderBy('name')
            ->get();

        $salesByBranch = Order::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', '!=', 'cancelled')
            ->when(
                $allowedBranchIds !== null,
                fn ($query) => $query->whereIn('branch_id', $allowedBranchIds)
            )
            ->selectRaw(
                'branch_id, COUNT(*) as order_count, ' .
                'SUM(subtotal) as subtotal, SUM(discount) as discount, ' .
                'SUM(tax) as tax, SUM(grand_total) as sales'
            )
            ->groupBy('branch_id')
            ->get()
            ->keyBy(fn ($row) => (string) ($row->branch_id ?? 'unassigned'));

        $branchSales = $branches->map(function (Branch $branch) use ($salesByBranch) {
            $totals = $salesByBranch->get((string) $branch->id);

            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'order_count' => (int) ($totals->order_count ?? 0),
                'subtotal' => (float) ($totals->subtotal ?? 0),
                'discount' => (float) ($totals->discount ?? 0),
                'tax' => (float) ($totals->tax ?? 0),
                'sales' => (float) ($totals->sales ?? 0),
            ];
        });

        if ($salesByBranch->has('unassigned')) {
            $totals = $salesByBranch->get('unassigned');
            $branchSales->push([
                'id' => null,
                'name' => 'Unassigned Branch',
                'order_count' => (int) $totals->order_count,
                'subtotal' => (float) $totals->subtotal,
                'discount' => (float) $totals->discount,
                'tax' => (float) $totals->tax,
                'sales' => (float) $totals->sales,
            ]);
        }

        $summary = [
            'order_count' => $branchSales->sum('order_count'),
            'subtotal' => $branchSales->sum('subtotal'),
            'discount' => $branchSales->sum('discount'),
            'tax' => $branchSales->sum('tax'),
            'sales' => $branchSales->sum('sales'),
        ];

        $ordersByBranch = Order::query()
            ->with(['customer', 'table', 'items'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', '!=', 'cancelled')
            ->when(
                $allowedBranchIds !== null,
                fn ($query) => $query->whereIn('branch_id', $allowedBranchIds)
            )
            ->latest()
            ->get()
            ->groupBy(fn (Order $order) => (string) ($order->branch_id ?? 'unassigned'));

        $branchDetails = $branchSales
            ->map(function (array $branch) use ($ordersByBranch) {
                $branchKey = (string) ($branch['id'] ?? 'unassigned');

                return [
                    'name' => $branch['name'],
                    'orders' => $ordersByBranch->get($branchKey, collect()),
                ];
            })
            ->values();

        return view('admin.reports.sales', compact(
            'branchSales',
            'branchDetails',
            'summary',
            'startDate',
            'endDate',
        ));
    }
}
