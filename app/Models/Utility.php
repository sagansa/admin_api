<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utility extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function utilityProvider()
    {
        return $this->belongsTo(UtilityProvider::class);
    }

    public function utilityUsages()
    {
        return $this->hasMany(UtilityUsage::class);
    }

    public function utilityBills()
    {
        return $this->hasMany(UtilityBill::class);
    }

    public function getUtilityNameAttribute()
    {
        return $this->store->nickname . ' | ' . $this->number . ' | ' . $this->utilityProvider->name;
    }

    public function getUtilityColumnNameAttribute()
    {
        return $this->store->nickname . ' | ' . $this->utilityProvider->name;
    }
}
