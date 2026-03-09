<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimplifiedStockMonitoringResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $coefficientColumn = $this->getCoefficientColumn();
        
        return [
            'date' => $this->created_at?->format('Y-m-d'),
            'name' => $this->name,
            'total_stock' => $this->stockMonitoringDetails->sum($coefficientColumn) ?? 0,
            'quantity_low' => $this->quantity_low,
        ];
    }

    private function getCoefficientColumn(): string
    {
        $columns = ['coefficient', 'coefisien', 'koefisien'];
        
        foreach ($columns as $column) {
            if ($this->stockMonitoringDetails->first()?->getAttribute($column) !== null) {
                return $column;
            }
        }
        
        return 'coefficient';
    }
}
