<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailSalesOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => $this->product->name,
            'sales_order_id' => $this->sales_order_id,
            'delivery_date' => $this->salesOrder->delivery_date,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
            'for' => $this->salesOrder->for,
            'payment_status' => $this->salesOrder->payment_status,
            'delivery_status' => $this->salesOrder->delivery_status,
            'store' => $this->salesOrder->store?->nickname ?? 'N/A',
            'order_by' => $this->salesOrder->order_by,
            'created_at' => $this->salesOrder->created_at,
            'updated_at' => $this->salesOrder->updated_at,
        ];
    }
}
