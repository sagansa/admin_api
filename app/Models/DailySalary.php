<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailySalary extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function shiftStore()
    {
        return $this->belongsTo(ShiftStore::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function closingStores()
    {
        return $this->belongsToMany(ClosingStore::class);
    }

    public function paymentReceipts()
    {
        return $this->belongsToMany(PaymentReceipt::class);
    }

    public function getDailySalaryNameAttribute()
    {
        return $this->createdBy->name .
            ' | ' . $this->date .
            ' | ' . $this->store->nickname .
            ' | Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function scopeForPaymentType(Builder $query, $paymentTypeId)
    {
        return $query->where('payment_type_id', $paymentTypeId)
            ->whereNull('payment_receipt_id')
            ->orderBy('date', 'asc');
    }
}
