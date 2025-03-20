<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductStorageStock extends Pivot
{
    public $timestamps = false;

    public function storageStock(): BelongsTo
    {
        return $this->belongsTo(StorageStock::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
