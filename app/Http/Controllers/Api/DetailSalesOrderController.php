<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailSalesOrderResource;
use App\Models\DetailSalesOrder;
use Illuminate\Http\Request;

class DetailSalesOrderController extends Controller
{
    public function index()
    {
        $detailSalesOrders = DetailSalesOrder::with(['product', 'salesOrder', 'salesOrderOnline', 'salesOrderDirect', 'salesOrderEmployee'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => DetailSalesOrderResource::collection($detailSalesOrders)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $detail = DetailSalesOrder::create($validated);
        $detail->load(['product', 'salesOrder']);

        return response()->json([
            'success' => true,
            'data' => $detail,
            'message' => 'Detail Sales Order created successfully'
        ], 201);
    }

    public function show(DetailSalesOrder $detailSalesOrder)
    {
        $detailSalesOrder->load(['product', 'salesOrder', 'salesOrderOnline', 'salesOrderDirect', 'salesOrderEmployee']);

        return response()->json([
            'success' => true,
            'data' => $detailSalesOrder
        ]);
    }

    public function update(Request $request, DetailSalesOrder $detailSalesOrder)
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|numeric|min:1',
            'price' => 'sometimes|required|numeric|min:0',
            'subtotal' => 'sometimes|required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
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
}