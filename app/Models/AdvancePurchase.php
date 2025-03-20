<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvancePurchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function detailAdvancePurchases()
    {
        return $this->hasMany(DetailAdvancePurchase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function cashAdvance()
    {
        return $this->belongsTo(CashAdvance::class);
    }

    public function getAdvancePurchaseNameAttribute()
    {
        return $this->date .
            ' | ' .
            $this->supplier->name .
            ' | ' .
            $this->user->name .
            ' | ' .
            number_format($this->total_price, 0);
    }

    // Calculate total price based on status 'valid'
    public static function calculateTotalPrice()
    {
        return self::where('status', 2)->sum('total_price');
    }
}
