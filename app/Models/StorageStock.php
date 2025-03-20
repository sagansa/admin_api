<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StorageStock extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_storage_stock')
            ->withPivot('quantity');
    }

    public function stockMonitoringDetails()
    {
        return $this->hasMany(StockMonitoringDetail::class);
    }
}
