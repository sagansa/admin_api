<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductPrice;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of product prices with pivot data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'store_id' => 'nullable|exists:stores,id',
            'status' => 'nullable|integer|in:0,1',
            'for' => 'nullable|string|in:active,inactive',
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:200',
        ]);

        $perPage = (int) ($request->per_page ?? 15);

        $query = ProductPrice::query()
            ->with([
                'product:id,name,unit_id',
                'product.unit:id,unit',
                'store:id,name,nickname',
            ])
            ->when($request->filled('store_id'), function ($q) use ($request) {
                return $q->where('store_id', $request->store_id);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->filled('for'), function ($q) use ($request) {
                // Filter 'for' dapat digunakan untuk logika custom sesuai kebutuhan
                // Contoh: filter berdasarkan kategori store atau atribut lainnya
                if ($request->for === 'active') {
                    return $q->whereHas('store', function ($storeQuery) {
                        $storeQuery->where('status', 1);
                    });
                } elseif ($request->for === 'inactive') {
                    return $q->whereHas('store', function ($storeQuery) {
                        $storeQuery->where('status', 0);
                    });
                }
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim((string) $request->search);
                return $q->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('product_id')
            ->orderBy('store_id');

        $prices = $query->paginate($perPage);

        return response()->json([
            'data' => $prices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'product_id' => $price->product_id,
                    'product_name' => $price->product?->name,
                    'product_unit' => $price->product?->unit?->unit,
                    'store_id' => $price->store_id,
                    'store_name' => $price->store?->name,
                    'store_nickname' => $price->store?->nickname,
                    'price' => (float) $price->price,
                    'formatted_price' => 'Rp ' . number_format($price->price, 0, ',', '.'),
                    'status' => (int) $price->status,
                    'status_label' => $price->status === 1 ? 'Active' : 'Inactive',
                    'created_at' => $price->created_at,
                    'updated_at' => $price->updated_at,
                ];
            }),
            'meta' => [
                'current_page' => $prices->currentPage(),
                'last_page' => $prices->lastPage(),
                'per_page' => $prices->perPage(),
                'total' => $prices->total(),
            ],
        ]);
    }

    /**
     * Store a new product price.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'store_id' => 'required|exists:stores,id',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|integer|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            // Check if price already exists for this product-store combination
            $existing = ProductPrice::where('product_id', $validated['product_id'])
                ->where('store_id', $validated['store_id'])
                ->first();

            if ($existing) {
                $existing->update([
                    'price' => $validated['price'],
                    'status' => $validated['status'] ?? $existing->status,
                ]);

                DB::commit();

                return response()->json([
                    'message' => 'Product price updated successfully',
                    'data' => $this->transformPrice(
                        $existing->load(['product', 'product.unit', 'store'])
                    ),
                ], 200);
            }

            $price = ProductPrice::create([
                'product_id' => $validated['product_id'],
                'store_id' => $validated['store_id'],
                'price' => $validated['price'],
                'status' => $validated['status'] ?? 1,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Product price created successfully',
                'data' => $this->transformPrice(
                    $price->load(['product', 'product.unit', 'store'])
                ),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to save product price',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified product price.
     * 
     * @param ProductPrice $productPrice
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ProductPrice $productPrice)
    {
        return response()->json([
            'data' => $this->transformPrice(
                $productPrice->load(['product', 'product.unit', 'store'])
            ),
        ]);
    }

    /**
     * Update the specified product price.
     * 
     * @param Request $request
     * @param ProductPrice $productPrice
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ProductPrice $productPrice)
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,id',
            'store_id' => 'sometimes|required|exists:stores,id',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => 'nullable|integer|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            $productPrice->update([
                'product_id' => $validated['product_id'] ?? $productPrice->product_id,
                'store_id' => $validated['store_id'] ?? $productPrice->store_id,
                'price' => $validated['price'] ?? $productPrice->price,
                'status' => $validated['status'] ?? $productPrice->status,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Product price updated successfully',
                'data' => $this->transformPrice(
                    $productPrice->load(['product', 'product.unit', 'store'])
                ),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update product price',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified product price.
     * 
     * @param ProductPrice $productPrice
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductPrice $productPrice)
    {
        try {
            DB::beginTransaction();
            $productPrice->delete();
            DB::commit();

            return response()->json([
                'message' => 'Product price deleted successfully',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete product price',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get product prices report with filters.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function report(Request $request)
    {
        $request->validate([
            'store_id' => 'nullable|exists:stores,id',
            'status' => 'nullable|integer|in:0,1',
            'for' => 'nullable|string|in:active,inactive',
        ]);

        $query = ProductPrice::query()
            ->with([
                'product:id,name,unit_id',
                'product.unit:id,unit',
                'store:id,name,nickname,type',
            ])
            ->when($request->filled('store_id'), function ($q) use ($request) {
                return $q->where('store_id', $request->store_id);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->filled('for'), function ($q) use ($request) {
                // Filter 'for' dapat digunakan untuk logika custom sesuai kebutuhan
                // Contoh: filter berdasarkan kategori store atau atribut lainnya
                if ($request->for === 'active') {
                    return $q->whereHas('store', function ($storeQuery) {
                        $storeQuery->where('status', 1);
                    });
                } elseif ($request->for === 'inactive') {
                    return $q->whereHas('store', function ($storeQuery) {
                        $storeQuery->where('status', 0);
                    });
                }
            })
            ->orderBy('store_id')
            ->orderBy('product_id')
            ->get();

        $report = $query->groupBy('store_id')->map(function ($prices, $storeId) {
            $store = $prices->first()->store;
            
            return [
                'store_id' => $storeId,
                'store_name' => $store?->name,
                'store_nickname' => $store?->nickname,
                'store_status' => $store?->status,
                'total_products' => $prices->count(),
                'active_prices' => $prices->where('status', 1)->count(),
                'inactive_prices' => $prices->where('status', 0)->count(),
                'average_price' => $prices->avg('price') ?? 0,
                'min_price' => $prices->min('price') ?? 0,
                'max_price' => $prices->max('price') ?? 0,
                'products' => $prices->map(function ($price) {
                    return [
                        'product_id' => $price->product_id,
                        'product_name' => $price->product?->name,
                        'product_unit' => $price->product?->unit?->unit,
                        'price' => (float) $price->price,
                        'formatted_price' => 'Rp ' . number_format($price->price, 0, ',', '.'),
                        'status' => (int) $price->status,
                        'status_label' => $price->status === 1 ? 'Active' : 'Inactive',
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'data' => $report,
        ]);
    }

    /**
     * Get prices by product across all stores.
     * 
     * @param Request $request
     * @param int $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function byProduct(Request $request, int $productId)
    {
        $request->validate([
            'status' => 'nullable|integer|in:0,1',
            'for' => 'nullable|string|in:active,inactive',
        ]);

        $product = Product::findOrFail($productId);

        $query = ProductPrice::query()
            ->where('product_id', $productId)
            ->with([
                'store:id,name,nickname',
            ])
            ->when($request->filled('status'), function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->filled('for'), function ($q) use ($request) {
                // Filter 'for' dapat digunakan untuk logika custom sesuai kebutuhan
                // Contoh: filter berdasarkan kategori store atau atribut lainnya
                if ($request->for === 'active') {
                    return $q->whereHas('store', function ($storeQuery) {
                        $storeQuery->where('status', 1);
                    });
                } elseif ($request->for === 'inactive') {
                    return $q->whereHas('store', function ($storeQuery) {
                        $storeQuery->where('status', 0);
                    });
                }
            })
            ->orderBy('store_id');

        $prices = $query->get();

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'unit' => $product->unit?->unit,
            ],
            'data' => $prices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'store_id' => $price->store_id,
                    'store_name' => $price->store?->name,
                    'store_nickname' => $price->store?->nickname,
                    'price' => (float) $price->price,
                    'formatted_price' => 'Rp ' . number_format($price->price, 0, ',', '.'),
                    'status' => (int) $price->status,
                    'status_label' => $price->status === 1 ? 'Active' : 'Inactive',
                ];
            }),
        ]);
    }

    /**
     * Get prices by store for all products.
     * 
     * @param Request $request
     * @param int $storeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function byStore(Request $request, int $storeId)
    {
        $request->validate([
            'status' => 'nullable|integer|in:0,1',
            'search' => 'nullable|string',
        ]);

        $store = Store::findOrFail($storeId);

        $query = ProductPrice::query()
            ->where('store_id', $storeId)
            ->with([
                'product:id,name,unit_id',
                'product.unit:id,unit',
            ])
            ->when($request->filled('status'), function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim((string) $request->search);
                return $q->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('product_id');

        $prices = $query->get();

        return response()->json([
            'store' => [
                'id' => $store->id,
                'name' => $store->name,
                'nickname' => $store->nickname,
            ],
            'data' => $prices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'product_id' => $price->product_id,
                    'product_name' => $price->product?->name,
                    'product_unit' => $price->product?->unit?->unit,
                    'price' => (float) $price->price,
                    'formatted_price' => 'Rp ' . number_format($price->price, 0, ',', '.'),
                    'status' => (int) $price->status,
                    'status_label' => $price->status === 1 ? 'Active' : 'Inactive',
                ];
            }),
        ]);
    }

    /**
     * Transform product price to array.
     * 
     * @param ProductPrice $price
     * @return array
     */
    private function transformPrice(ProductPrice $price): array
    {
        return [
            'id' => $price->id,
            'product_id' => $price->product_id,
            'product_name' => $price->product?->name,
            'product_unit' => $price->product?->unit?->unit,
            'store_id' => $price->store_id,
            'store_name' => $price->store?->name,
            'store_nickname' => $price->store?->nickname,
            'price' => (float) $price->price,
            'formatted_price' => 'Rp ' . number_format($price->price, 0, ',', '.'),
            'status' => (int) $price->status,
            'status_label' => $price->status === 1 ? 'Active' : 'Inactive',
            'created_at' => $price->created_at,
            'updated_at' => $price->updated_at,
        ];
    }
}
