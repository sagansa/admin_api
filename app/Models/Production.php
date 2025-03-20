<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Production extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function productionSupportFroms()
    {
        return $this->hasMany(ProductionSupportFrom::class);
    }

    public function productionTos()
    {
        return $this->hasMany(ProductionTo::class);
    }

    public function productionMainFroms()
    {
        return $this->hasMany(ProductionMainFrom::class);
    }
}
