<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashAdvance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advancePurchases()
    {
        return $this->hasMany(AdvancePurchase::class);
    }

    public function getCashAdvanceNameAttribute()
    {
        return
            ' Rp ' .
            number_format($this->transfer,0) .
            ' | ' .
            $this->date;
    }
}
