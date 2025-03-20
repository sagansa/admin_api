<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreAsset extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function movementAssets()
    {
        return $this->hasMany(MovementAsset::class);
    }
}
