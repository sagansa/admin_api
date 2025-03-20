<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fuelServices()
    {
        return $this->hasMany(FuelService::class);
    }

    public function vehicleTaxes()
    {
        return $this->hasMany(VehicleTax::class);
    }

    public function vehicleCertificates()
    {
        return $this->hasMany(VehicleCertificate::class);
    }

    public function getVehicleStatusAttribute()
    {
        $statuses = [
            1 => 'active',
            2 => 'inactive',
        ];

        return $this->no_register . ' - ' . $statuses[$this->status];
    }
}
