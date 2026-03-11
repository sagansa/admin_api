<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetailStockCardRequest;
use App\Http\Resources\DetailStockCardResource;
use App\Models\DetailStockCard;
use App\Models\StockMonitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailStockCardController extends Controller
{
    public function index(Request $request)
    {
        $query = DetailStockCard::with(['product', 'stockCard']);

        if ($request->has('stock_card_id')) {
            $query->where('stock_card_id', $request->stock_card_id);
        }

        $detailStockCards = $query->latest()->get();

        return DetailStockCardResource::collection($detailStockCards);
    }

    public function dailyStockCard(Request $request)
    {
        $query = DetailStockCard::with(['product', 'stockCard'])
            ->whereHas('stockCard', function ($query) {
                $query->where('date', '>=', now()->subDays(3)->startOfDay())
                    ->where('date', '<=', now()->endOfDay());
            });

        if ($request->has('stock_card_id')) {
            $query->where('stock_card_id', $request->stock_card_id);
        }

        $detailStockCards = $query->latest()->get();

        return DetailStockCardResource::collection($detailStockCards);
    }

    public function monthlyStockCard(Request $request)
    {
        $query = DetailStockCard::with(['product', 'stockCard'])
            ->whereHas('stockCard', function ($query) {
                $query->whereRaw('DATE(date) = LAST_DAY(date)');
            });

        if ($request->has('stock_card_id')) {
            $query->where('stock_card_id', $request->stock_card_id);
        }

        $detailStockCards = $query->latest()->get();

        return DetailStockCardResource::collection($detailStockCards);
    }

    public function yearlyStockCard(Request $request)
    {
        $query = DetailStockCard::with(['product', 'stockCard'])
            ->whereHas('stockCard', function ($query) {
                $query->whereRaw('DATE(date) = LAST_DAY(DATE_FORMAT(date, "%Y-12-01"))');
            });

        if ($request->has('stock_card_id')) {
            $query->where('stock_card_id', $request->stock_card_id);
        }

        $detailStockCards = $query->latest()->get();

        return DetailStockCardResource::collection($detailStockCards);
    }

    public function store(DetailStockCardRequest $request)
    {
        $detailStockCard = DetailStockCard::create($request->validated());

        return new DetailStockCardResource($detailStockCard);
    }

    public function show(DetailStockCard $detailStockCard)
    {
        return new DetailStockCardResource($detailStockCard->load(['product', 'stockCard']));
    }

    public function update(DetailStockCardRequest $request, DetailStockCard $detailStockCard)
    {
        $detailStockCard->update($request->validated());

        return new DetailStockCardResource($detailStockCard);
    }

    public function destroy(DetailStockCard $detailStockCard)
    {
        $detailStockCard->delete();

        return response()->noContent();
    }

    /**
     * Compare Sales Orders vs Stock Cards between two dates
     * Both starting from Stock Monitoring
     * Stock Period: from_date (start) to to_date (end)
     * Sales: only from to_date
     */
    public function compareStockPeriods(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        // Determine the correct coefficient column name
        $coefficientColumn = $this->getCoefficientColumn();

        // 1. Get Sales Orders data - only from to_date
        // First aggregate sales by product_id, then join with stock_monitoring_details to apply coefficient
        $salesByProduct = DB::table('detail_sales_orders')
            ->join('sales_orders', 'detail_sales_orders.sales_order_id', '=', 'sales_orders.id')
            ->whereDate('sales_orders.delivery_date', $toDate)
            ->select(
                'detail_sales_orders.product_id',
                DB::raw('SUM(detail_sales_orders.quantity) as total_quantity'),
                DB::raw('SUM(detail_sales_orders.subtotal_price) as total_value')
            )
            ->groupBy('detail_sales_orders.product_id');

        $salesQuery = DB::table('stock_monitorings')
            ->join('stock_monitoring_details', 'stock_monitoring_details.stock_monitoring_id', '=', 'stock_monitorings.id')
            ->leftJoinSub($salesByProduct, 'sales_by_product', function ($join) {
                $join->on('stock_monitoring_details.product_id', '=', 'sales_by_product.product_id');
            })
            ->selectRaw('
                stock_monitorings.id as stock_monitoring_id,
                stock_monitorings.name as stock_monitoring_name,
                COALESCE(SUM(sales_by_product.total_quantity * stock_monitoring_details.' . $coefficientColumn . '), 0) as total_sales_quantity,
                COALESCE(SUM(sales_by_product.total_value), 0) as total_sales_value
            ')
            ->groupBy('stock_monitorings.id', 'stock_monitorings.name');

        $salesData = $salesQuery->get()->keyBy('stock_monitoring_id');

        // 2. Get Stock Cards at from_date - Latest stock snapshot per product ON OR BEFORE from_date
        $stockFromQuery = DB::table('stock_monitorings')
            ->select([
                'stock_monitorings.id as stock_monitoring_id',
                'stock_monitorings.name as stock_monitoring_name',
                DB::raw('COALESCE(SUM(latest_from.quantity * stock_monitoring_details.' . $coefficientColumn . '), 0) as total_quantity'),
            ])
            ->join('stock_monitoring_details', 'stock_monitoring_details.stock_monitoring_id', '=', 'stock_monitorings.id')
            ->join('products', 'stock_monitoring_details.product_id', '=', 'products.id')
            ->leftJoin(DB::raw('(
                SELECT dsc.product_id, dsc.quantity
                FROM detail_stock_cards dsc
                INNER JOIN stock_cards sc ON dsc.stock_card_id = sc.id
                INNER JOIN (
                    SELECT dsc2.product_id, MAX(sc2.date) as max_date
                    FROM detail_stock_cards dsc2
                    INNER JOIN stock_cards sc2 ON dsc2.stock_card_id = sc2.id
                    WHERE sc2.date <= "' . $fromDate . '"
                    GROUP BY dsc2.product_id
                ) latest ON sc.date = latest.max_date AND dsc.product_id = latest.product_id
                WHERE sc.date <= "' . $fromDate . '"
            ) latest_from'), 'latest_from.product_id', '=', 'products.id')
            ->groupBy('stock_monitorings.id', 'stock_monitorings.name');

        $stockFromData = $stockFromQuery->get()->keyBy('stock_monitoring_id');

        // 3. Get Stock Cards at to_date - Latest stock snapshot per product ON OR BEFORE to_date
        $stockToQuery = DB::table('stock_monitorings')
            ->select([
                'stock_monitorings.id as stock_monitoring_id',
                'stock_monitorings.name as stock_monitoring_name',
                DB::raw('COALESCE(SUM(latest_to.quantity * stock_monitoring_details.' . $coefficientColumn . '), 0) as total_quantity'),
            ])
            ->join('stock_monitoring_details', 'stock_monitoring_details.stock_monitoring_id', '=', 'stock_monitorings.id')
            ->join('products', 'stock_monitoring_details.product_id', '=', 'products.id')
            ->leftJoin(DB::raw('(
                SELECT dsc.product_id, dsc.quantity
                FROM detail_stock_cards dsc
                INNER JOIN stock_cards sc ON dsc.stock_card_id = sc.id
                INNER JOIN (
                    SELECT dsc2.product_id, MAX(sc2.date) as max_date
                    FROM detail_stock_cards dsc2
                    INNER JOIN stock_cards sc2 ON dsc2.stock_card_id = sc2.id
                    WHERE sc2.date <= "' . $toDate . '"
                    GROUP BY dsc2.product_id
                ) latest ON sc.date = latest.max_date AND dsc.product_id = latest.product_id
                WHERE sc.date <= "' . $toDate . '"
            ) latest_to'), 'latest_to.product_id', '=', 'products.id')
            ->groupBy('stock_monitorings.id', 'stock_monitorings.name');

        $stockToData = $stockToQuery->get()->keyBy('stock_monitoring_id');

        // Merge all stock monitoring IDs
        $allStockMonitoringIds = collect([
            ...$salesData->keys(),
            ...$stockFromData->keys(),
            ...$stockToData->keys()
        ])->unique();

        // Build comparison data
        $comparison = $allStockMonitoringIds->map(function ($id) use ($salesData, $stockFromData, $stockToData) {
            $salesQty = isset($salesData[$id]) ? (float) $salesData[$id]->total_sales_quantity : 0;
            $salesValue = isset($salesData[$id]) ? (float) $salesData[$id]->total_sales_value : 0;
            $stockFromQty = isset($stockFromData[$id]) ? (float) $stockFromData[$id]->total_quantity : 0;
            $stockToQty = isset($stockToData[$id]) ? (float) $stockToData[$id]->total_quantity : 0;
            
            // Stock change: to_date - from_date
            // Negative = stock decreased (sold), Positive = stock increased (restock)
            $stockChange = $stockToQty - $stockFromQty;
            
            // Variance: Compare Sales vs Stock Change
            // If stock decreased by 10 and sales = 10 → balanced (variance = 0)
            // If stock decreased by 10 but sales = 15 → over_sold (variance = 5)
            // If stock decreased by 10 but sales = 5 → under_sold (variance = -5, potential loss)
            $variance = $salesQty + $stockChange; // stockChange is negative when stock decreases

            return [
                'stock_monitoring_id' => $id,
                'stock_monitoring_name' => $salesData[$id]->stock_monitoring_name 
                    ?? $stockFromData[$id]->stock_monitoring_name 
                    ?? $stockToData[$id]->stock_monitoring_name ?? 'N/A',
                'sales_quantity' => $salesQty,
                'sales_value' => $salesValue,
                'stock_from_quantity' => $stockFromQty,
                'stock_to_quantity' => $stockToQty,
                'stock_change' => $stockChange,
                'variance' => $variance,
                'status' => abs($variance) < 0.01 ? 'balanced' : ($variance > 0 ? 'over_sold' : 'under_sold'),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $comparison,
            'summary' => [
                'period' => [
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                ],
                'sales' => [
                    'total_quantity' => $comparison->sum('sales_quantity'),
                    'total_value' => $comparison->sum('sales_value'),
                ],
                'stock' => [
                    'from_quantity' => $comparison->sum('stock_from_quantity'),
                    'to_quantity' => $comparison->sum('stock_to_quantity'),
                    'total_change' => $comparison->sum('stock_change'),
                ],
                'total_variance' => $comparison->sum('variance'),
                'over_sold_items' => $comparison->where('status', 'over_sold')->count(),
                'under_sold_items' => $comparison->where('status', 'under_sold')->count(),
                'balanced_items' => $comparison->where('status', 'balanced')->count(),
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