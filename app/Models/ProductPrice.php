<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'store_id',
        'price',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relasi
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeByStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeWithVariant($query)
    {
        return $query->whereNotNull('product_variant_id');
    }

    public function scopeWithoutVariant($query)
    {
        return $query->whereNull('product_variant_id');
    }

    // Methods
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    public function getPriceHistory()
    {
        // Implementasi jika ada table price_history
        return $this->hasMany(PriceHistory::class);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($price) {
            // Validasi harga tidak boleh negatif
            if ($price->price < 0) {
                throw new \Exception('Price cannot be negative');
            }
        });
    }
}
