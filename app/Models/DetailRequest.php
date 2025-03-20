<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function requestPurchase()
    {
        return $this->belongsTo(RequestPurchase::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function detailInvoices()
    {
        return $this->hasMany(DetailInvoice::class);
    }

    public function getDetailRequestNameAttribute()
    {
        return $this->product->name . ' | ' . $this->requestPurchase->date;
    }

    public function scopeByInvoicePurchase($query, $storeId, $paymentTypeId)
    {
        return $query->where('store_id', $storeId)->where('payment_type_id', $paymentTypeId);
    }


}
