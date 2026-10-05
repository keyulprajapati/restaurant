<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RestaurantTableController extends Controller
{
    public function index(Request $request)
    {
        $query = RestaurantTable::query()->with('activeOrder');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'table_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'area',
                    'like',
                    "%{$search}%"
                );
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('area')) {
            $query->where(
                'area',
                $request->area
            );
        }

        $tables = $query
            ->orderBy('table_number')
            ->paginate(15)
            ->withQueryString();

        $areas = RestaurantTable::query()
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->distinct()
            ->orderBy('area')
            ->pluck('area');

        return view(
            'admin.tables.index',
            compact(
                'tables',
                'areas'
            )
        );
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function createExtra()
    {
        $areas = RestaurantTable::query()
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->distinct()
            ->orderBy('area')
            ->pluck('area');

        $lastTable = RestaurantTable::orderBy('id', 'desc')->first();
        $suggestedStart = 1;
        if ($lastTable && preg_match('/(\d+)$/', $lastTable->table_number, $matches)) {
            $suggestedStart = ((int) $matches[1]) + 1;
        }

        return view('admin.tables.extra', compact('areas', 'suggestedStart'));
    }

    public function storeExtra(Request $request)
    {
        $validated = $request->validate([
            'prefix' => ['nullable', 'string', 'max:15'],
            'start_number' => ['required', 'integer', 'min:1'],
            'count' => ['required', 'integer', 'min:1', 'max:50'],
            'pad_zeros' => ['required', 'integer', 'min:0', 'max:4'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'custom_area' => ['nullable', 'string', 'max:100'],
            'table_type' => ['required', Rule::in(['regular', 'round', 'square', 'outdoor', 'private'])],
            'notes' => ['nullable', 'string'],
        ]);

        $prefix = trim($validated['prefix'] ?? 'T');
        $startNumber = (int) $validated['start_number'];
        $count = (int) $validated['count'];
        $padZeros = (int) $validated['pad_zeros'];
        $area = !empty($validated['custom_area']) ? trim($validated['custom_area']) : ($validated['area'] ?? null);
        $isActive = $request->boolean('is_active', true);

        $createdCount = 0;
        $skippedTables = [];

        DB::transaction(function () use (
            $prefix, $startNumber, $count, $padZeros, $validated, $area, $isActive, &$createdCount, &$skippedTables
        ) {
            for ($i = 0; $i < $count; $i++) {
                $num = $startNumber + $i;
                $formattedNum = $padZeros > 0 ? str_pad($num, $padZeros, '0', STR_PAD_LEFT) : (string) $num;
                $tableNumber = $prefix . $formattedNum;

                if (RestaurantTable::where('table_number', $tableNumber)->exists()) {
                    $skippedTables[] = $tableNumber;
                    continue;
                }

                RestaurantTable::create([
                    'table_number' => $tableNumber,
                    'name' => 'Table ' . $formattedNum,
                    'capacity' => $validated['capacity'],
                    'area' => $area,
                    'table_type' => $validated['table_type'],
                    'status' => 'available',
                    'is_active' => $isActive,
                    'notes' => $validated['notes'] ?? null,
                ]);

                $createdCount++;
            }
        });

        $message = "Successfully created {$createdCount} extra table(s).";
        if (!empty($skippedTables)) {
            $message .= " Skipped existing: " . implode(', ', $skippedTables) . ".";
        }

        return redirect()->route('admin.tables.index')->with('success', $message);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                'unique:restaurant_tables,table_number',
            ],

            'name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'area' => [
                'nullable',
                'string',
                'max:100',
            ],

            'table_type' => [
                'required',
                Rule::in([
                    'regular',
                    'round',
                    'square',
                    'outdoor',
                    'private',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        RestaurantTable::create([
            'table_number' => $validated['table_number'],
            'name' => $validated['name'] ?? null,
            'capacity' => $validated['capacity'],
            'area' => $validated['area'] ?? null,
            'table_type' => $validated['table_type'],
            'status' => 'available',
            'is_active' => $request->boolean('is_active', true),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.tables.index')
            ->with(
                'success',
                'Restaurant table created successfully.'
            );
    }

    public function edit(RestaurantTable $table)
    {
        return view(
            'admin.tables.edit',
            compact('table')
        );
    }

    public function update(
        Request $request,
        RestaurantTable $table
    ) {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'restaurant_tables',
                    'table_number'
                )->ignore($table->id),
            ],

            'name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'area' => [
                'nullable',
                'string',
                'max:100',
            ],

            'table_type' => [
                'required',
                Rule::in([
                    'regular',
                    'round',
                    'square',
                    'outdoor',
                    'private',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'occupied',
                    'reserved',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $table->update([
            'table_number' => $validated['table_number'],
            'name' => $validated['name'] ?? null,
            'capacity' => $validated['capacity'],
            'area' => $validated['area'] ?? null,
            'table_type' => $validated['table_type'],
            'status' => $validated['status'],
            'is_active' => $request->boolean('is_active'),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.tables.index')
            ->with(
                'success',
                'Restaurant table updated successfully.'
            );
    }

    public function qrCode(Request $request, RestaurantTable $table)
    {
        $customBase = $request->query('base_url');
        if (!empty($customBase)) {
            $url = rtrim($customBase, '/') . '/table/' . $table->table_number;
        } else {
            $url = $table->url;
        }

        $svg = QrCodeService::generateSvg($url, 260);

        return response()->json([
            'table_id' => $table->id,
            'table_number' => $table->table_number,
            'name' => $table->name,
            'area' => $table->area,
            'capacity' => $table->capacity,
            'url' => $url,
            'svg' => $svg,
            'detected_ip' => gethostbyname(gethostname()),
            'current_base' => setting('qr_base_url', 'http://' . gethostbyname(gethostname()) . '/restaurant/public'),
        ]);
    }

    public function saveQrBaseUrl(Request $request)
    {
        $request->validate([
            'qr_base_url' => ['required', 'string', 'max:255'],
        ]);

        $cleanUrl = rtrim($request->qr_base_url, '/');
        \App\Models\Setting::set('qr_base_url', $cleanUrl);

        return response()->json([
            'success' => true,
            'message' => 'QR Code Mobile Base URL updated successfully to ' . $cleanUrl,
            'qr_base_url' => $cleanUrl,
        ]);
    }

    public function printQr(RestaurantTable $table)
    {
        $url = $table->url;
        $svg = QrCodeService::generateSvg($url, 260);

        return view('admin.tables.print_qr', compact('table', 'url', 'svg'));
    }

    public function printAllQr(Request $request)
    {
        $query = RestaurantTable::query()->where('is_active', true);
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        $tables = $query->orderBy('table_number')->get()->map(function ($table) {
            $table->order_url = $table->url;
            $table->svg = QrCodeService::generateSvg($table->order_url, 200);
            return $table;
        });

        return view('admin.tables.print_all_qr', compact('tables'));
    }

    public function destroy(RestaurantTable $table)
    {
        if ($table->status === 'occupied') {
            return back()->with(
                'error',
                'An occupied table cannot be deleted.'
            );
        }

        $table->delete();

        return redirect()
            ->route('admin.tables.index')
            ->with(
                'success',
                'Restaurant table deleted successfully.'
            );
    }
}