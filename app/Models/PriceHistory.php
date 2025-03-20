<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_price_id',
        'old_price',
        'new_price',
        'changed_by_id',
        'change_reason',
        'effective_date',
        'status'
    ];

    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'effective_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'status' => 'integer'
    ];

    // Relasi ke ProductPrice
    public function productPrice()
    {
        return $this->belongsTo(ProductPrice::class);
    }

    // Relasi ke User yang mengubah harga
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_id');
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('effective_date', [$startDate, $endDate]);
    }

    // Method untuk mendapatkan persentase perubahan harga
    public function getPriceChangePercentageAttribute()
    {
        if ($this->old_price == 0) return 100;
        return (($this->new_price - $this->old_price) / $this->old_price) * 100;
    }

    // Method untuk mendapatkan tipe perubahan (naik/turun)
    public function getPriceChangeTypeAttribute()
    {
        if ($this->new_price > $this->old_price) return 'increase';
        if ($this->new_price < $this->old_price) return 'decrease';
        return 'no_change';
    }

    // Method untuk format harga
    public function getFormattedOldPriceAttribute()
    {
        return number_format($this->old_price, 2);
    }

    public function getFormattedNewPriceAttribute()
    {
        return number_format($this->new_price, 2);
    }
}
