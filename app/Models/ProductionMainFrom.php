<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionMainFrom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function detailInvoice()
    {
        return $this->belongsTo(DetailInvoice::class);
    }
}
