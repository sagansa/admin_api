<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FuelServicePaymentReceipt extends Pivot
{
    public $timestamps = false;

    public function fuelService(): BelongsTo
    {
        return $this->belongsTo(FuelService::class);
    }

    public function PaymentReceipt(): BelongsTo
    {
        return $this->belongsTo(PaymentReceipt::class);
    }
}
