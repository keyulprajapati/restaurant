<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Kot;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KotController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedBranchIds = $user->allowedBranchIds();
        $branches = Branch::query()
            ->where('is_active', true)
            ->when($allowedBranchIds !== null, fn ($query) => $query->whereIn('id', $allowedBranchIds))
            ->orderBy('name')
            ->get();

        $query = Kot::with([
            'order.customer',
            'order.branch',
            'table',
            'items',
        ])->whereHas('order', function ($query) use ($allowedBranchIds) {
            if ($allowedBranchIds !== null) {
                $query->whereIn('branch_id', $allowedBranchIds);
            }
        });

        if ($request->filled('branch_id')) {
            $this->ensureBranchAccess((int) $request->branch_id);
            $query->whereHas('order', fn ($query) => $query->where('branch_id', $request->branch_id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kots = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.kots.index', compact('kots', 'branches'));
    }

    public function create(Order $order)
    {
        $this->ensureOrderAccess($order);
        $order->load(['items', 'customer', 'table']);

        return view('admin.kots.create', compact('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.order_item_id' => [
                'required',
                Rule::exists('order_items', 'id')->where('order_id', $request->input('order_id')),
            ],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.notes' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $order = Order::with(['items', 'table'])->findOrFail($validated['order_id']);
        $this->ensureOrderAccess($order);

        $kot = DB::transaction(function () use ($validated, $order) {
            $kot = Kot::create([
                'kot_number' => $this->generateKotNumber(),
                'order_id' => $order->id,
                'restaurant_table_id' => $order->restaurant_table_id,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'sent_at' => now(),
            ]);

            foreach ($validated['items'] as $item) {
                $orderItem = $order->items->firstWhere('id', $item['order_item_id']);
                if (!$orderItem) {
                    continue;
                }

                $kot->items()->create([
                    'order_item_id' => $orderItem->id,
                    'item_name' => $orderItem->item_name,
                    'size' => $orderItem->size,
                    'quantity' => $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                    'status' => 'pending',
                ]);
            }

            $order->update(['status' => 'preparing']);

            return $kot;
        });

        return redirect()
            ->route('admin.kots.show', $kot)
            ->with('success', 'KOT created successfully.');
    }

    public function show(Kot $kot)
    {
        $this->ensureKotAccess($kot);
        $kot->load(['order.customer', 'order.table', 'order.branch', 'items']);

        return view('admin.kots.show', compact('kot'));
    }

    public function updateStatus(Request $request, Kot $kot)
    {
        $this->ensureKotAccess($kot);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,preparing,ready,served,cancelled'],
        ]);

        $data = ['status' => $validated['status']];

        switch ($validated['status']) {
            case 'preparing':
                $data['prepared_at'] = now();
                break;
            case 'ready':
                $data['ready_at'] = now();
                break;
            case 'served':
                $data['served_at'] = now();
                break;
        }

        DB::transaction(function () use ($kot, $data) {
            $kot->update($data);
            $kot->items()->update(['status' => $data['status']]);

            if (in_array($data['status'], ['preparing', 'ready', 'served'], true)) {
                $kot->order->update(['status' => $data['status']]);
            }
        });

        return back()->with('success', 'KOT status updated successfully.');
    }

    public function print(Kot $kot)
    {
        $this->ensureKotAccess($kot);
        $kot->load(['order.customer', 'order.table', 'order.branch', 'items']);

        return view('admin.kots.print', compact('kot'));
    }

    private function ensureOrderAccess(Order $order): void
    {
        $user = auth()->user();
        $hasAccess = $order->branch_id
            ? $user->canAccessBranch((int) $order->branch_id)
            : $user->all_branches;

        abort_unless($hasAccess, 403);
    }

    private function ensureKotAccess(Kot $kot): void
    {
        $this->ensureOrderAccess($kot->loadMissing('order')->order);
    }

    private function ensureBranchAccess(int $branchId): void
    {
        abort_unless(auth()->user()->canAccessBranch($branchId), 403);
    }

    private function generateKotNumber(): string
    {
        do {
            $number = 'KOT-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        } while (Kot::where('kot_number', $number)->exists());

        return $number;
    }
}