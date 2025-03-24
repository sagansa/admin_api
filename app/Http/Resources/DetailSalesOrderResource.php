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
            'unit_price' => (string) $this->unit_price,
            'subtotal_price' => (string) $this->subtotal_price,
            'for' => match($this->salesOrder->for) {
                '1' => 'Direct',
                '2' => 'Employee',
                '3' => 'Online',
                default => 'unknown'
            },
            // 'payment_status_code' => $this->salesOrder->payment_status,
            'payment_status' => match($this->salesOrder->payment_status) {
                1 => 'belum diperiksa',
                2 => 'valid',
                3 => 'perbaiki',
                4 => 'periksa ulang',
                default => 'unknown'
            },
            // 'delivery_status_code' => $this->salesOrder->delivery_status,
            'delivery_status' => match($this->salesOrder->delivery_status) {
                1 => 'belum dikirim',
                2 => 'valid',
                3 => 'sudah dikirim',
                4 => 'siap dikirim',
                5 => 'perbaiki',
                6 => 'dikembalikan',
                default => 'unknown'
            },
            'store' => $this->salesOrder->store?->nickname ?? 'N/A',
            'order_by' => $this->salesOrder->orderedBy->name,
            'created_at' => $this->salesOrder->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->salesOrder->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
