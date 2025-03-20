<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferCardStorage extends Model
{
    use HasFactory;

    protected $table = 'transfer_cards';

    protected $guarded = [];

    protected $dates = ['date'];

    public function storeFrom()
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    public function storeTo()
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }

    public function detailTransferCards()
    {
        return $this->hasMany(DetailTransferCard::class, 'transfer_card_id');
    }
}
