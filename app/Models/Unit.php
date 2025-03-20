<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function detailInvoices()
    {
        return $this->hasMany(DetailInvoice::class);
    }

    public function utilities()
    {
        return $this->hasMany(Utility::class);
    }

    public function stockMonitorings()
    {
        return $this->hasMany(StockMonitoring::class);
    }
}
