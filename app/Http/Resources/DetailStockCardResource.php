<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailStockCardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'stock_card_id' => $this->stock_card_id,
            'product_name' => $this->product->name,
            'store_name' => $this->stockCard->store->nickname ?? 'N/A',
            'date' => $this->stockCard->date ?? 'N/A',
            'for' => $this->stockCard->for ?? 'N/A',
            'quantity' => $this->quantity,
            'unit' => $this->product->unit->unit,
            'created_at' => $this->stockCard->created_at ?? 'N/A',
            'updated_at' => $this->stockCard->updated_at ?? 'N/A',
        ];
    }
}