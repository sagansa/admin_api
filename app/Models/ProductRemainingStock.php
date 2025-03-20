<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductRemainingStock extends Pivot
{
    public $timestamps = false;

    public function remainingStock(): BelongsTo
    {
        return $this->belongsTo(RemainingStock::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
