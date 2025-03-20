<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UtilityBill extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function utility()
    {
        return $this->belongsTo(Utility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
