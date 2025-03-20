<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DailySalaryPaymentReceipt extends Pivot
{
    public $timestamps = false;

    public function dailySalary(): BelongsTo
    {
        return $this->belongsTo(DailySalary::class);
    }

    public function PaymentReceipt(): BelongsTo
    {
        return $this->belongsTo(PaymentReceipt::class);
    }
}
