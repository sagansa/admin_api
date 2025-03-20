<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function closingStores()
    {
        return $this->belongsToMany(ClosingStore::class);
    }

    public function paymentReceipts()
    {
        return $this->belongsToMany(PaymentReceipt::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function getFuelServiceNameAttribute()
    {
        // return $this->vehicle->no_register .
        //     ' | Rp ' . number_format($this->amount, 0, ',', '.') .
        //     ' | ' . $this->created_at .
        //     ' | ' . $this->createdBy->name;

        $fuelServiceDetails = [
            ($this->vehicle->no_register ? : ''),
            ($this->date ? : ''),
            ($this->createdBy->name ? : ''),
            ('Rp ' . number_format($this->amount) ? : ''),
        ];

        return implode("\n", $fuelServiceDetails);
    }
}
