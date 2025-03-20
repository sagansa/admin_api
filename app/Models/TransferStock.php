<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferStock extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function storeFrom()
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    public function storeTo()
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function productTransferStocks(): HasMany
    {
        return $this->hasMany(ProductTransferStock::class);
    }
}
