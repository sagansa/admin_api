<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreCashless extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function accountCashlesses()
    {
        return $this->hasMany(AccountCashless::class);
    }

    public function userCashlesses()
    {
        return $this->hasMany(UserCashless::class);
    }
}
