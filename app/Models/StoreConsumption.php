<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreConsumption extends Model
{
    use HasFactory;

    protected $table = 'stock_cards';

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailStockCards()
    {
        return $this->hasMany(DetailStockCard::class, 'stock_card_id');
    }
}
