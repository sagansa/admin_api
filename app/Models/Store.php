<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function closingStores()
    {
        return $this->hasMany(ClosingStore::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountCashlesses()
    {
        return $this->hasMany(AccountCashless::class);
    }

    public function advancePurchases()
    {
        return $this->hasMany(AdvancePurchase::class);
    }

    public function dailySalaries()
    {
        return $this->hasMany(DailySalary::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function invoicePurchases()
    {
        return $this->hasMany(InvoicePurchase::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function requestPurchases()
    {
        return $this->hasMany(RequestPurchase::class);
    }

    public function detailRequests()
    {
        return $this->hasMany(DetailRequest::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function remainingStocks()
    {
        return $this->hasMany(RemainingStock::class);
    }

    public function selfConsumptions()
    {
        return $this->hasMany(SelfConsumption::class);
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function transferStocksStoreFrom()
    {
        return $this->hasMany(TransferStock::class, 'from_store_id');
    }

    public function transferStocksStoreTo()
    {
        return $this->hasMany(TransferStock::class, 'to_store_id');
    }

    public function utilities()
    {
        return $this->hasMany(Utility::class);
    }

    public function storeAssets()
    {
        return $this->hasMany(StoreAsset::class);
    }

    public function movementAssetResults()
    {
        return $this->hasMany(MovementAssetResult::class);
    }

    public function hygienes()
    {
        return $this->hasMany(Hygiene::class);
    }

    public function shiftStores()
    {
        return $this->hasMany(ShiftStore::class);
    }

    public function userCashlesses()
    {
        return $this->hasMany(UserCashless::class);
    }

    public function storageStocks()
    {
        return $this->hasMany(StorageStock::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function stockCards()
    {
        return $this->hasMany(StockCard::class);
    }

    public function transferCardsStoreFrom()
    {
        return $this->hasMany(TransferCard::class, 'from_store_id');
    }

    public function transferCardsStoreTo()
    {
        return $this->hasMany(TransferCard::class, 'to_store_id');
    }
}
