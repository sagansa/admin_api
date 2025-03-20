<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractLocation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
