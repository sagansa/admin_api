<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dailySalaries()
    {
        return $this->hasMany(DailySalary::class);
    }

    public function fuelServices()
    {
        return $this->hasMany(FuelService::class);
    }

    public function invoicePurchases()
    {
        return $this->hasMany(InvoicePurchase::class);
    }

    public function detailRequests()
    {
        return $this->hasMany(DetailRequest::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
