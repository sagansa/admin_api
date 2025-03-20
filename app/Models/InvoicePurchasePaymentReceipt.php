<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InvoicePurchasePaymentReceipt extends Pivot
{
    public $timestamps = false;

    public function invoicePurchase(): BelongsTo
    {
        return $this->belongsTo(InvoicePurchase::class);
    }

    public function PaymentReceipt(): BelongsTo
    {
        return $this->belongsTo(PaymentReceipt::class);
    }
}
