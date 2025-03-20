<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminCashless extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cashlessProvider()
    {
        return $this->belongsTo(CashlessProvider::class);
    }

    public function accountCashlesses()
    {
        return $this->belongsToMany(AccountCashless::class);
    }

    public function userCashlesses()
    {
        return $this->belongsToMany(UserCashless::class);
    }
}
