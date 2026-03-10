<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailSalesOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product' => $this->product?->name ?? 'N/A',
            'delivery_date' => $this->salesOrder?->delivery_date,
            'quantity' => $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'subtotal_price' => (float) $this->subtotal_price,
            'for' => match($this->salesOrder?->for) {
                '1' => 'Direct',
                '2' => 'Employee',
                '3' => 'Online',
                default => 'unknown'
            },
            'payment_status' => match($this->salesOrder?->payment_status) {
                1 => 'belum diperiksa',
                2 => 'valid',
                3 => 'perbaiki',
                4 => 'periksa ulang',
                default => 'unknown'
            },
            'delivery_status' => match($this->salesOrder?->delivery_status) {
                1 => 'belum dikirim',
                2 => 'valid',
                3 => 'sudah dikirim',
                4 => 'siap dikirim',
                5 => 'perbaiki',
                6 => 'dikembalikan',
                default => 'unknown'
            },
            'status' => $this->getStatusAttribute(),
        ];
    }

    private function getStatusAttribute(): string
    {
        $paymentStatus = $this->salesOrder?->payment_status;
        $deliveryStatus = $this->salesOrder?->delivery_status;

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
}
