<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockMonitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class StockMonitoringController extends Controller
{
    protected function stockMonitoringRelations(): array
    {
        return [
            'unit:id,unit',
            'stockMonitoringDetails',
            'stockMonitoringDetails.product:id,name,unit_id',
            'stockMonitoringDetails.product.unit:id,unit',
        ];
    }

    protected function detailCoefficientColumn(): string
    {
        $columns = Schema::getColumnListing('stock_monitoring_details');

        foreach (['coefficient', 'coefisien', 'koefisien'] as $column) {
            if (in_array($column, $columns, true)) {
                return $column;
            }
        }

        return 'coefisien';
    }

    protected function normalizeDetails(array $details): array
    {
        $coefficientColumn = $this->detailCoefficientColumn();

        return collect($details)
            ->map(function ($detail) use ($coefficientColumn) {
                $coefficient = $detail['coefficient']
                    ?? $detail['coefisien']
                    ?? $detail['koefisien']
                    ?? 1;

                return [
                    'product_id' => $detail['product_id'],
                    $coefficientColumn => $coefficient,
                ];
            })
            ->values()
            ->all();
    }

    protected function detailRules(bool $required = true): array
    {
        return [
            'details' => [$required ? 'required' : 'sometimes', 'array', 'min:1'],
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.coefficient' => 'nullable|numeric',
            'details.*.coefisien' => 'nullable|numeric',
            'details.*.koefisien' => 'nullable|numeric',
        ];
    }

    protected function transformStockMonitoring(StockMonitoring $stockMonitoring): array
    {
        $coefficientColumn = $this->detailCoefficientColumn();

        return [
            'id' => $stockMonitoring->id,
            'name' => $stockMonitoring->name,
            'quantity_low' => $stockMonitoring->quantity_low,
            'category' => $stockMonitoring->category,
            'unit_id' => $stockMonitoring->unit_id,
            'unit' => $stockMonitoring->unit,
            'created_at' => $stockMonitoring->created_at,
            'updated_at' => $stockMonitoring->updated_at,
            'details' => $stockMonitoring->stockMonitoringDetails
                ->map(function ($detail) use ($coefficientColumn) {
                    return [
                        'id' => $detail->id,
                        'product_id' => $detail->product_id,
                        'stock_monitoring_id' => $detail->stock_monitoring_id,
                        'coefficient' => (float) ($detail->{$coefficientColumn} ?? 1),
                        'product' => $detail->product,
                    ];
                })
                ->values(),
            'stock_monitoring_details' => $stockMonitoring->stockMonitoringDetails
                ->map(function ($detail) use ($coefficientColumn) {
                    return [
                        'id' => $detail->id,
                        'product_id' => $detail->product_id,
                        'stock_monitoring_id' => $detail->stock_monitoring_id,
                        'coefficient' => (float) ($detail->{$coefficientColumn} ?? 1),
                        'product' => $detail->product,
                    ];
                })
                ->values(),
        ];
    }

    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'category' => ['nullable', Rule::in(['storage', 'store'])],
            'unit_id' => 'nullable|exists:units,id',
            'per_page' => 'nullable|integer|min:1|max:200',
        ]);

        $perPage = (int) ($request->per_page ?? 15);

        $query = StockMonitoring::query()
            ->with($this->stockMonitoringRelations())
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim((string) $request->search);

                return $q->where('name', 'like', '%' . $search . '%');
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                return $q->where('category', $request->category);
            })
            ->when($request->filled('unit_id'), function ($q) use ($request) {
                return $q->where('unit_id', $request->unit_id);
            })
            ->orderBy('name')
            ->orderBy('id');

        return response()->json([
            'data' => $query->paginate($perPage)->through(function (StockMonitoring $stockMonitoring) {
                return $this->transformStockMonitoring($stockMonitoring);
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(array_merge([
            'name' => 'required|string|max:255',
            'quantity_low' => 'required|numeric|min:0',
            'category' => ['required', Rule::in(['storage', 'store'])],
            'unit_id' => 'required|exists:units,id',
        ], $this->detailRules()));

        try {
            DB::beginTransaction();

            $stockMonitoring = StockMonitoring::create([
                'name' => $validated['name'],
                'quantity_low' => $validated['quantity_low'],
                'category' => $validated['category'],
                'unit_id' => $validated['unit_id'],
            ]);

            foreach ($this->normalizeDetails($validated['details']) as $detail) {
                $stockMonitoring->stockMonitoringDetails()->create($detail);
            }

            DB::commit();

            return response()->json([
                'message' => 'Stock monitoring created successfully',
                'data' => $this->transformStockMonitoring(
                    $stockMonitoring->load($this->stockMonitoringRelations())
                ),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create stock monitoring',
            ], 500);
        }
    }

    public function show(StockMonitoring $stockMonitoring)
    {
        return response()->json([
            'data' => $this->transformStockMonitoring(
                $stockMonitoring->load($this->stockMonitoringRelations())
            ),
        ]);
    }

    public function update(Request $request, StockMonitoring $stockMonitoring)
    {
        $validated = $request->validate(array_merge([
            'name' => 'sometimes|required|string|max:255',
            'quantity_low' => 'sometimes|required|numeric|min:0',
            'category' => ['sometimes', 'required', Rule::in(['storage', 'store'])],
            'unit_id' => 'sometimes|required|exists:units,id',
        ], $this->detailRules(false)));

        try {
            DB::beginTransaction();

            $stockMonitoring->update([
                'name' => $validated['name'] ?? $stockMonitoring->name,
                'quantity_low' => $validated['quantity_low'] ?? $stockMonitoring->quantity_low,
                'category' => $validated['category'] ?? $stockMonitoring->category,
                'unit_id' => $validated['unit_id'] ?? $stockMonitoring->unit_id,
            ]);

            if (array_key_exists('details', $validated)) {
                $stockMonitoring->stockMonitoringDetails()->delete();

                foreach ($this->normalizeDetails($validated['details']) as $detail) {
                    $stockMonitoring->stockMonitoringDetails()->create($detail);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Stock monitoring updated successfully',
                'data' => $this->transformStockMonitoring(
                    $stockMonitoring->load($this->stockMonitoringRelations())
                ),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update stock monitoring',
            ], 500);
        }
    }

    public function destroy(StockMonitoring $stockMonitoring)
    {
        try {
            DB::beginTransaction();
            $stockMonitoring->stockMonitoringDetails()->delete();
            $stockMonitoring->delete();
            DB::commit();

            return response()->json([
                'message' => 'Stock monitoring deleted successfully',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete stock monitoring',
            ], 500);
        }
    }

    public function report(Request $request)
    {
        $request->validate([
            'category' => ['nullable', Rule::in(['storage', 'store'])],
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $report = StockMonitoring::query()
            ->with($this->stockMonitoringRelations())
            ->when($request->filled('category'), function ($q) use ($request) {
                return $q->where('category', $request->category);
            })
            ->when($request->filled('unit_id'), function ($q) use ($request) {
                return $q->where('unit_id', $request->unit_id);
            })
            ->orderBy('name')
            ->get()
            ->map(function (StockMonitoring $item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'quantity_low' => $item->quantity_low,
                    'unit' => $item->unit?->unit,
                    'total_details' => $item->stockMonitoringDetails->count(),
                    'products' => $item->stockMonitoringDetails
                        ->map(function ($detail) {
                            return [
                                'product_id' => $detail->product_id,
                                'product_name' => $detail->product?->name,
                                'unit' => $detail->product?->unit?->unit,
                                'coefficient' => (float) (
                                    $detail->coefficient
                                    ?? $detail->coefisien
                                    ?? $detail->koefisien
                                    ?? 1
                                ),
                            ];
                        })
                        ->values(),
                ];
            });

        return response()->json([
            'data' => $report,
        ]);
    }
}
