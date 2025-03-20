<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovementAsset extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storeAsset()
    {
        return $this->belongsTo(StoreAsset::class);
    }

    public function movementAssetAudits()
    {
        return $this->hasMany(MovementAssetAudit::class);
    }
}
