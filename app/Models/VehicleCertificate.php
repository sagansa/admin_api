<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleCertificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
