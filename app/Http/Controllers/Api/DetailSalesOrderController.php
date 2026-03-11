<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailSalesOrderResource;
use App\Models\DetailSalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailSalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'for' => 'nullable|in:1,2,3,Direct,Employee,Online',
        ]);

        $query = DetailSalesOrder::query()
            ->with(['product', 'salesOrder'])
            ->when($request->filled('date'), function ($q) use ($request) {
                return $q->whereHas('salesOrder', function ($q2) use ($request) {
                    $q2->whereDate('delivery_date', $request->date);
                });
            })
            ->when($request->filled('for'), function ($q) use ($request) {
                $forValue = $request->for;
                // Map string values to numeric
                $forMap = [
                    'Direct' => '1',
                    'Employee' => '2',
                    'Online' => '3'
                ];
                $forValue = $forMap[$forValue] ?? $forValue;
                return $q->whereHas('salesOrder', function ($q2) use ($forValue) {
                    $q2->where('for', $forValue);
                });
            })
            ->orderByHasOne('salesOrder', 'delivery_date', 'desc')
            ->latest();

        $detailSalesOrders = $query->get();

        return response()->json([
            'success' => true,
            'data' => DetailSalesOrderResource::collection($detailSalesOrders)
        ]);
    }

    /**
     * Get daily product sales status with store information
     */
    public function dailyProductStatus(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        $selectedDate = $request->date;

        $query = DetailSalesOrder::query()
            ->select([
                'detail_sales_orders.id',
                'detail_sales_orders.product_id',
                'detail_sales_orders.sales_order_id',
                'detail_sales_orders.quantity',
                'detail_sales_orders.unit_price',
                'detail_sales_orders.subtotal_price',
                'sales_orders.for',
                'sales_orders.store_id',
                'sales_orders.delivery_date',
            ])
            ->join('sales_orders', 'detail_sales_orders.sales_order_id', '=', 'sales_orders.id')
            ->join('products', 'detail_sales_orders.product_id', '=', 'products.id')
            ->leftJoin('stores', 'sales_orders.store_id', '=', 'stores.id')
            ->with(['product:id,name', 'salesOrder:id,for,store_id,delivery_date,payment_status,delivery_status'])
            ->whereDate('sales_orders.delivery_date', $selectedDate);

        // Filter by store if provided
        if ($request->filled('store_id')) {
            $query->where('sales_orders.store_id', $request->store_id);
        }

        $sales = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name ?? 'N/A',
                'store_id' => $item->store_id,
                'store_name' => $item->salesOrder?->store?->nickname ?? 'N/A',
                'delivery_date' => $item->delivery_date,
                'for' => match($item->for) {
                    '1' => 'Direct',
                    '2' => 'Employee',
                    '3' => 'Online',
                    default => 'unknown'
                },
                'quantity' => (int) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'total_price' => (float) $item->subtotal_price,
                'payment_status' => match($item->salesOrder?->payment_status) {
                    1 => 'belum diperiksa',
                    2 => 'valid',
                    3 => 'perbaiki',
                    4 => 'periksa ulang',
                    default => 'unknown'
                },
                'delivery_status' => match($item->salesOrder?->delivery_status) {
                    1 => 'belum dikirim',
                    2 => 'valid',
                    3 => 'sudah dikirim',
                    4 => 'siap dikirim',
                    5 => 'perbaiki',
                    6 => 'dikembalikan',
                    default => 'unknown'
                },
                'status' => $this->calculateStatus($item->salesOrder?->payment_status, $item->salesOrder?->delivery_status),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $sales,
            'summary' => [
                'total_items' => $sales->count(),
                'total_quantity' => $sales->sum('quantity'),
                'total_revenue' => $sales->sum('total_price'),
                'by_for' => [
                    'Direct' => $sales->where('for', 'Direct')->count(),
                    'Employee' => $sales->where('for', 'Employee')->count(),
                    'Online' => $sales->where('for', 'Online')->count(),
                ],
                'by_status' => [
                    'completed' => $sales->where('status', 'completed')->count(),
                    'processing' => $sales->where('status', 'processing')->count(),
                    'pending' => $sales->where('status', 'pending')->count(),
                    'issues' => $sales->whereIn('status', ['payment_issue', 'returned'])->count(),
                ],
            ]
        ]);
    }

    /**
     * Calculate order status based on payment and delivery status
     */
    private function calculateStatus(?int $paymentStatus, ?int $deliveryStatus): string
    {
        if ($paymentStatus === 2 && $deliveryStatus === 3) {
            return 'completed';
        }

        if ($paymentStatus === 2 && in_array($deliveryStatus, [4, 5])) {
            return 'processing';
        }

        if (in_array($paymentStatus, [3, 4])) {
            return 'payment_issue';
        }

        if ($deliveryStatus === 6) {
            return 'returned';
        }

        return 'pending';
    }

    public function update(Request $request, DetailSalesOrder $detailSalesOrder)
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|numeric|min:1',
            'unit_price' => 'sometimes|required|numeric|min:0',
            'subtotal_price' => 'sometimes|required|numeric|min:0',
        ]);

        $detailSalesOrder->update($validated);
        $detailSalesOrder->load(['product', 'salesOrder']);

        return response()->json([
            'success' => true,
            'data' => $detailSalesOrder,
            'message' => 'Detail Sales Order updated successfully'
        ]);
    }

    public function destroy(DetailSalesOrder $detailSalesOrder)
    {
        $detailSalesOrder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Detail Sales Order deleted successfully'
        ]);
    }

    public function getBySalesOrder($salesOrderId)
    {
        $details = DetailSalesOrder::with(['product'])
            ->where('sales_order_id', $salesOrderId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $details
        ]);
    }

    /**
     * Get sales data by date range with quantity and total price for each product
     * Starting from StockMonitoring and joining with DetailSalesOrder
     */
    public function salesByDate(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'for' => 'nullable|in:1,2,3,Direct,Employee,Online',
        ]);

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        // Determine the correct coefficient column name
        $coefficientColumn = $this->getCoefficientColumn();

        $query = DB::table('stock_monitorings')
            ->select([
                'stock_monitorings.name as stock_monitoring_name',
                DB::raw('SUM(detail_sales_orders.quantity * stock_monitoring_details.' . $coefficientColumn . ') as total_quantity'),
                DB::raw('SUM(detail_sales_orders.subtotal_price * stock_monitoring_details.' . $coefficientColumn . ') as total_price'),
                DB::raw('AVG(detail_sales_orders.unit_price) as avg_unit_price'),
                'sales_orders.delivery_status',
                'sales_orders.payment_status',
            ])
            ->join('stock_monitoring_details', 'stock_monitoring_details.stock_monitoring_id', '=', 'stock_monitorings.id')
            ->join('products', 'stock_monitoring_details.product_id', '=', 'products.id')
            ->join('detail_sales_orders', 'detail_sales_orders.product_id', '=', 'products.id')
            ->join('sales_orders', 'detail_sales_orders.sales_order_id', '=', 'sales_orders.id')
            ->whereBetween('sales_orders.delivery_date', [$fromDate, $toDate])
            ->groupBy('stock_monitorings.name', 'sales_orders.delivery_status', 'sales_orders.payment_status');

        // Filter by sales order type if provided
        if ($request->filled('for')) {
            $forValue = $request->for;
            $forMap = [
                'Direct' => '1',
                'Employee' => '2',
                'Online' => '3'
            ];
            $forValue = $forMap[$forValue] ?? $forValue;
            $query->where('sales_orders.for', $forValue);
        }

        $sales = $query->get()
            ->map(function ($item) {
                $quantity = (int) $item->total_quantity;
                $totalPrice = (float) $item->total_price;

                return [
                    'stock_monitoring_name' => $item->stock_monitoring_name ?? 'N/A',
                    'quantity' => $quantity,
                    'unit_price' => (float) $item->avg_unit_price,
                    'total_price' => $totalPrice,
                    'delivery_status' => match((int) $item->delivery_status) {
                        1 => 'belum dikirim',
                        2 => 'valid',
                        3 => 'sudah dikirim',
                        4 => 'siap dikirim',
                        5 => 'perbaiki',
                        6 => 'dikembalikan',
                        default => 'unknown'
                    },
                    'payment_status' => match((int) $item->payment_status) {
                        1 => 'belum diperiksa',
                        2 => 'valid',
                        3 => 'perbaiki',
                        4 => 'periksa ulang',
                        default => 'unknown'
                    },
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $sales,
            'summary' => [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'total_products' => $sales->count(),
                'total_quantity' => $sales->sum('quantity'),
                'total_revenue' => $sales->sum('total_price'),
            ]
        ]);
    }

    /**
     * Get the coefficient column name from stock_monitoring_details
     */
    private function getCoefficientColumn(): string
    {
        $columns = ['coefficient', 'coefisien', 'koefisien'];
        
        try {
            $schema = DB::getDoctrineSchemaManager();
            $columnsFound = array_map(fn($c) => $c['Field'], $schema->listTableColumns('stock_monitoring_details'));
            
            foreach ($columns as $column) {
                if (in_array($column, $columnsFound)) {
                    return $column;
                }
            }
        } catch (\Exception $e) {
            // Fallback to default
        }
        
        return 'coefficient';
    }
}