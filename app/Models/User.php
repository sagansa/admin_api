<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['profile_photo_url'];

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all of the vehicles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Get all of the utilityUsagesCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function utilityUsagesCreatedBy()
    {
        return $this->hasMany(UtilityUsage::class, 'created_by_id');
    }

    /**
     * Get all of the utilityUsagesApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function utilityUsagesApprovedBy()
    {
        return $this->hasMany(UtilityUsage::class, 'approved_by_id');
    }

    /**
     * Get all of the vehicleTaxes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicleTaxes()
    {
        return $this->hasMany(VehicleTax::class);
    }

    /**
     * Get all of the employees.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get all of the contractEmployees.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contractEmployees()
    {
        return $this->hasMany(ContractEmployee::class);
    }

    /**
     * Get all of the closingCouriersCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closingCouriersCreatedBy()
    {
        return $this->hasMany(ClosingCourier::class, 'created_by_id');
    }

    /**
     * Get all of the closingCouriersApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closingCouriersApprovedBy()
    {
        return $this->hasMany(ClosingCourier::class, 'approved_by_id');
    }

    /**
     * Get all of the stores.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    /**
     * Get all of the advancePurchases.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function advancePurchases()
    {
        return $this->hasMany(AdvancePurchase::class);
    }

    /**
     * Get all of the products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all of the suppliers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    /**
     * Get all of the closingStoresTransferBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closingStoresTransferBy()
    {
        return $this->hasMany(ClosingStore::class, 'transfer_by_id');
    }

    /**
     * Get all of the closingStoresCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closingStoresCreatedBy()
    {
        return $this->hasMany(ClosingStore::class, 'created_by_id');
    }

    /**
     * Get all of the closingStoresApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closingStoresApprovedBy()
    {
        return $this->hasMany(ClosingStore::class, 'approved_by_id');
    }

    /**
     * Get all of the dailySalariesCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dailySalariesCreatedBy()
    {
        return $this->hasMany(DailySalary::class, 'created_by_id');
    }

    /**
     * Get all of the dailySalariesApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dailySalariesApprovedBy()
    {
        return $this->hasMany(DailySalary::class, 'approved_by_id');
    }

    /**
     * Get all of the requestPurchases.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestPurchases()
    {
        return $this->hasMany(RequestPurchase::class);
    }

    /**
     * Get all of the productionsCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionsCreatedBy()
    {
        return $this->hasMany(Production::class, 'created_by_id');
    }

    /**
     * Get all of the productionsApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionsApprovedBy()
    {
        return $this->hasMany(Production::class, 'approved_by_id');
    }

    /**
     * Get all of the remainingStocksCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function remainingStocksCreatedBy()
    {
        return $this->hasMany(RemainingStock::class, 'created_by_id');
    }

    /**
     * Get all of the remainingStocksApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function remainingStocksApprovedBy()
    {
        return $this->hasMany(RemainingStock::class, 'approved_by_id');
    }

    /**
     * Get all of the selfConsumptionsCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function selfConsumptionsCreatedBy()
    {
        return $this->hasMany(SelfConsumption::class, 'created_by_id');
    }

    /**
     * Get all of the selfConsumptionsApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function selfConsumptionsApprovedBy()
    {
        return $this->hasMany(SelfConsumption::class, 'approved_by_id');
    }

    /**
     * Get all of the salesOrdersOrderedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salesOrdersOrderedBy()
    {
        return $this->hasMany(SalesOrder::class, 'ordered_by_id');
    }

    /**
     * Get all of the salesOrdersAssignedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salesOrdersAssignedBy()
    {
        return $this->hasMany(SalesOrder::class, 'assigned_by_id');
    }

    /**
     * Get all of the transferStocksApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferStocksApprovedBy()
    {
        return $this->hasMany(TransferStock::class, 'approved_by_id');
    }

    /**
     * Get all of the transferStocksReceivedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferStocksReceivedBy()
    {
        return $this->hasMany(TransferStock::class, 'received_by_id');
    }

    /**
     * Get all of the transferStocksSentBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferStocksSentBy()
    {
        return $this->hasMany(TransferStock::class, 'sent_by_id');
    }

    /**
     * Get all of the movementAssetResults.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movementAssetResults()
    {
        return $this->hasMany(MovementAssetResult::class);
    }

    /**
     * Get all of the readinessesCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function readinessesCreatedBy()
    {
        return $this->hasMany(Readiness::class, 'created_by_id');
    }

    /**
     * Get all of the readinessesApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function readinessesApprovedBy()
    {
        return $this->hasMany(Readiness::class, 'approved_by_id');
    }

    /**
     * Get all of the hygienesCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hygienesCreatedBy()
    {
        return $this->hasMany(Hygiene::class, 'created_by_id');
    }

    /**
     * Get all of the hygienesApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hygienesApprovedBy()
    {
        return $this->hasMany(Hygiene::class, 'approved_by_id');
    }

    /**
     * Get all of the fuelServicesCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fuelServicesCreatedBy()
    {
        return $this->hasMany(FuelService::class, 'created_by_id');
    }

    /**
     * Get all of the fuelServicesApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fuelServicesApprovedBy()
    {
        return $this->hasMany(FuelService::class, 'approved_by_id');
    }

    /**
     * Get all of the invoicePurchasesCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoicePurchasesCreatedBy()
    {
        return $this->hasMany(InvoicePurchase::class, 'created_by_id');
    }

    /**
     * Get all of the invoicePurchasesApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoicePurchasesApprovedBy()
    {
        return $this->hasMany(InvoicePurchase::class, 'approved_by_id');
    }

    /**
     * Get all of the permitEmployeesCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permitEmployeesCreatedBy()
    {
        return $this->hasMany(PermitEmployee::class, 'created_by_id');
    }

    /**
     * Get all of the permitEmployeesApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permitEmployeesApprovedBy()
    {
        return $this->hasMany(PermitEmployee::class, 'approved_by_id');
    }

    /**
     * Get all of the materialGroups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materialGroups()
    {
        return $this->hasMany(MaterialGroup::class);
    }

    /**
     * Get all of the cashAdvances.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cashAdvances()
    {
        return $this->hasMany(CashAdvance::class);
    }

    /**
     * Get all of the deliveryAddresses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveryAddresses()
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    /**
     * Get all of the utilityBills.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function utilityBills()
    {
        return $this->hasMany(UtilityBill::class);
    }

    /**
     * Get all of the storageStocksCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storageStocksCreatedBy()
    {
        return $this->hasMany(StorageStock::class, 'created_by_id');
    }

    /**
     * Get all of the storageStocksApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storageStocksApprovedBy()
    {
        return $this->hasMany(StorageStock::class, 'approved_by_id');
    }

    /**
     * Get all of the paymentReceipts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentReceipts()
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    /**
     * Get all of the presencesCreatedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presencesCreatedBy()
    {
        return $this->hasMany(Presence::class, 'created_by_id');
    }

    /**
     * Get all of the presencesApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presencesApprovedBy()
    {
        return $this->hasMany(Presence::class, 'approved_by_id');
    }

    /**
     * Get all of the stockCards.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockCards()
    {
        return $this->hasMany(StockCard::class);
    }

    /**
     * Get all of the transferCardsApprovedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferCardsApprovedBy()
    {
        return $this->hasMany(TransferCard::class, 'approved_by_id');
    }

    /**
     * Get all of the transferCardsSentBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferCardsSentBy()
    {
        return $this->hasMany(TransferCard::class, 'sent_by_id');
    }

    /**
     * Get all of the transferCardsReceivedBy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferCardsReceivedBy()
    {
        return $this->hasMany(TransferCard::class, 'received_by_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (User $user) {
            $user->assignRole('customer');
        });
    }

    public function calculateSalary($startDate, $endDate)
    {
        // Ambil data employee
        $employee = $this->employees()->first();

        if (!$employee || !$employee->join_date) {
            throw new \Exception('Employee data or join date not found');
        }

        // Hitung masa kerja berdasarkan join_date employee
        $yearsOfService = Carbon::parse($employee->join_date)->diffInYears(Carbon::now());

        // Ambil rate gaji yang berlaku
        $salaryRate = SalaryRate::where('effective_date', '<=', $endDate)
            ->latest('effective_date')
            ->first();

        if (!$salaryRate) {
            throw new \Exception('Salary rate not found');
        }

        // Ambil rate per jam sesuai masa kerja
        $rateDetail = $salaryRate->salaryRateDetails()
            ->where('years_of_service', '<=', $yearsOfService)
            ->orderBy('years_of_service', 'desc')
            ->first();

        if (!$rateDetail) {
            throw new \Exception('Rate detail not found');
        }

        // Hitung total jam kerja dari presensi
        $totalHours = Presence::where('created_by_id', $this->id)
            ->whereDate('check_in', '>=', $startDate)
            ->whereDate('check_in', '<=', $endDate)
            ->whereNotNull('check_out')
            ->get()
            ->sum(function ($presence) {
                $checkIn = Carbon::parse($presence->check_in);
                $checkOut = Carbon::parse($presence->check_out);
                return $checkOut->diffInHours($checkIn);
            });

        // Hitung total gaji
        $totalSalary = $totalHours * $rateDetail->rate_per_hour;

        return [
            'employee' => [
                'id' => $this->id,
                'name' => $this->name,
                'employee_id' => $employee->id,
                'join_date' => $employee->join_date,
                'years_of_service' => $yearsOfService,
                'bank_name' => $employee->bank->name ?? null,
                'account_number' => $employee->account_number ?? null,
                'account_name' => $employee->account_name ?? null
            ],
            'salary_rate' => [
                'name' => $salaryRate->name,
                'effective_date' => $salaryRate->effective_date,
                'rate_per_hour' => $rateDetail->rate_per_hour
            ],
            'calculation' => [
                'total_hours' => $totalHours,
                'rate_per_hour' => $rateDetail->rate_per_hour,
                'total_salary' => $totalSalary
            ],
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
