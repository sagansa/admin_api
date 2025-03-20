<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSelfConsumption extends Pivot
{
    public $timestamps = false;

    public function selfConsumption(): BelongsTo
    {
        return $this->belongsTo(SelfConsumption::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
