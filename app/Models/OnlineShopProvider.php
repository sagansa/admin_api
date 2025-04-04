<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OnlineShopProvider extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
}
