<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RemainingStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemainingStorageController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'store_id' => 'nullable|exists:stores,id',
            'date' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:200',
        ]);

        $perPage = (int) ($request->per_page ?? 15);

        $query = RemainingStorage::query()
            ->with([
                'store:id,nickname,name',
                'user:id,name,username',
                'detailStockCards:id,product_id,quantity,stock_card_id',
                'detailStockCards.product:id,name,unit_id',
                'detailStockCards.product.unit:id,unit,nickname',
            ])
            ->where('for', 'remaining storage')
            ->when($request->store_id, function ($q) use ($request) {
                return $q->where('store_id', $request->store_id);
            })
            ->when($request->date, function ($q) use ($request) {
                return $q->whereDate('date', $request->date);
            })
            ->orderByDesc('date')
            ->orderByDesc('id');

        return response()->json([
            'data' => $query->paginate($perPage)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|numeric|min:0',
            'details.*.unit_id' => 'required|exists:units,id'
        ]);

        try {
            DB::beginTransaction();

            $remainingStorage = RemainingStorage::create([
                'for' => 'remaining storage',
                'store_id' => $request->store_id,
                'user_id' => $request->user_id,
                'date' => $request->date,
                'description' => $request->description
            ]);

            foreach ($request->details as $detail) {
                $remainingStorage->detailStockCards()->create([
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'unit_id' => $detail['unit_id']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Remaining storage created successfully',
                'data' => $remainingStorage->load([
                    'store:id,nickname,name',
                    'user:id,name,username',
                    'detailStockCards:id,product_id,quantity,stock_card_id',
                    'detailStockCards.product:id,name,unit_id',
                    'detailStockCards.product.unit:id,unit,nickname',
                ])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create remaining storage'], 500);
        }
    }

    public function show(RemainingStorage $remainingStorage)
    {
        if (strtolower((string) $remainingStorage->for) !== 'remaining storage') {
            return response()->json(['message' => 'Remaining storage not found'], 404);
        }

        return response()->json([
            'data' => $remainingStorage->load([
                'store:id,nickname,name',
                'user:id,name,username',
                'detailStockCards:id,product_id,quantity,stock_card_id',
                'detailStockCards.product:id,name,unit_id',
                'detailStockCards.product.unit:id,unit,nickname',
            ])
        ]);
    }

    public function update(Request $request, RemainingStorage $remainingStorage)
    {
        $request->validate([
            'store_id' => 'sometimes|required|exists:stores,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'date' => 'sometimes|required|date',
            'description' => 'nullable|string',
            'details' => 'sometimes|required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|numeric|min:0',
            'details.*.unit_id' => 'required|exists:units,id'
        ]);

        try {
            DB::beginTransaction();

            $remainingStorage->update([
                'store_id' => $request->store_id ?? $remainingStorage->store_id,
                'user_id' => $request->user_id ?? $remainingStorage->user_id,
                'date' => $request->date ?? $remainingStorage->date,
                'description' => $request->description
            ]);

            if ($request->has('details')) {
                $remainingStorage->detailStockCards()->delete();
                foreach ($request->details as $detail) {
                    $remainingStorage->detailStockCards()->create([
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'unit_id' => $detail['unit_id']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Remaining storage updated successfully',
                'data' => $remainingStorage->load([
                    'store:id,nickname,name',
                    'user:id,name,username',
                    'detailStockCards:id,product_id,quantity,stock_card_id',
                    'detailStockCards.product:id,name,unit_id',
                    'detailStockCards.product.unit:id,unit,nickname',
                ])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update remaining storage'], 500);
        }
    }

    public function destroy(RemainingStorage $remainingStorage)
    {
        try {
            DB::beginTransaction();
            $remainingStorage->detailStockCards()->delete();
            $remainingStorage->delete();
            DB::commit();

            return response()->json(['message' => 'Remaining storage deleted successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete remaining storage'], 500);
        }
    }

    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'store_id' => 'nullable|exists:stores,id'
        ]);

        $query = RemainingStorage::with(['store', 'detailStockCards'])
            ->where('for', 'remaining storage')
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->when($request->store_id, function ($q) use ($request) {
                return $q->where('store_id', $request->store_id);
            });

        $report = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'store_name' => $item->store->nickname ?? $item->store->name ?? null,
                'date' => $item->date,
                'total_products' => $item->detailStockCards->count(),
                'total_quantity' => $item->detailStockCards->sum('quantity')
            ];
        });

        return response()->json(['data' => $report]);
    }
}
