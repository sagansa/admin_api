<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMonitoring extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockMonitoringDetails()
    {
        return $this->hasMany(StockMonitoringDetail::class);
    }
}
