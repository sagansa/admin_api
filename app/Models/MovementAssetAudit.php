<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovementAssetAudit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function movementAsset()
    {
        return $this->belongsTo(MovementAsset::class);
    }

    public function movementAssetResult()
    {
        return $this->belongsTo(MovementAssetResult::class);
    }
}
