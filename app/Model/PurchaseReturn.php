<?php

namespace App\Model;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Concession;
use App\Models\Locker;
use App\Models\Part;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseReturnLibrary;
use App\Models\RevenueReceipt;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyTerm;
use App\Models\TaxesFees;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseReturn extends Model
{
    use LogsActivity;

    protected $table = 'purchase_returns';

    protected $fillable = [
        'date',
        'invoice_number',
        'branch_id',
        'purchase_invoice_id',
        'date',
        'time',
        'type',
        'discount_type',
        'discount',
        'total',
        'total_after_discount',
        'supplier_discount_status',
        'supplier_discount_type',
        'supplier_discount',
        'tax',
        'invoice_type',
        'sub_total',
        'status',
        'additional_payments',
        'supplier_id',
        'library_path'
    ];


    protected static $logAttributes = ['invoice_number', 'date', 'time', 'number_of_items', 'total_after_discount', 'is_discount_group_added'];

    protected static $logOnlyDirty = true;

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'date' => 'date',
        'invoice_number' => 'invoice_number',
        'supplier_name' => 'supplier_name',
        'type' => 'type',
        'total' => 'total',
        'paid' => 'paid',
        'remaining' => 'remaining',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class, 'purchase_returns_id');
    }

    public function revenueReceipt(): HasMany
    {
        return $this->hasMany(RevenueReceipt::class, 'purchase_return_id');
    }

    public function getPaidAttribute()
    {
        return $this->revenueReceipt->sum('cost');
    }

    public function getRemainingAttribute()
    {
        return $this->total - $this->revenueReceipt->sum('cost');
    }

    public function deleteInvoice()
    {
        DB::transaction(function () {
            foreach ($this->items()->get() as $item) {

                $part = Part::find($item->part_id);

                if ($part) {

                    $quantityInPart = $part->quantity;

                    $part->update([
                        'quantity' => $quantityInPart + $item->purchase_qty,
                    ]);
                }

                $purchaseInvoice = $this->invoice;

                if ($purchaseInvoice) {

                    $invoice_item = $purchaseInvoice->items()->where('part_id', $part->id)->first();

                    if ($invoice_item) {

                        $invoice_item->purchase_qty += $item->purchase_qty;
                        $invoice_item->save();
                    }
                }
            }

            $this->items()->delete();
            $this->removeBulkBalance($this->revenueReceipt()->get());
            $this->revenueReceipt()->delete();
            parent::delete();
        });
    }

    public function removeBulkBalance(Collection $collection)
    {
        foreach ($collection as $col) {
            if ($col->locker_id !== null) {
                $this->removeBalanceFromLocker($col);
            }
            if ($col->account_id !== null) {
                $this->removeBalanceFromAccount($col);
            }
        }
    }

    public function removeBalanceFromLocker(RevenueReceipt $receipt)
    {
        $locker = Locker::find($receipt->locker_id);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance - $receipt->cost)
            ]);
        }

    }

    public function removeBalanceFromAccount(RevenueReceipt $receipt)
    {
        $account = Account::find($receipt->account_id);
        if ($account) {
            $account->update([
                'balance' => ($account->balance - $receipt->cost)
            ]);
        }
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'purchase_return_taxes_fees', 'purchase_return_id', 'tax_id');
    }

    public function supplyOrders()
    {
        return $this->belongsToMany(SupplyOrder::class, 'purchase_return_supply_orders');
    }

    public function purchaseReceipts()
    {
        return $this->belongsToMany(PurchaseReceipt::class, 'purchase_return_purchase_receipts');
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }

    public function getNumberAttribute()
    {
        return $this->invoice_number;
    }

    public function getSupplierNameAttribute()
    {

        if ($this->invoice_type == 'from_supply_order') {

            return $this->supplyOrders->first() && $this->supplyOrders->first()->supplier ?  $this->supplyOrders->first()->supplier->name : '---';
        }

        return $this->invoice && $this->invoice->supplier ? $this->invoice->supplier->name : '---';
    }

    public function terms()
    {
        return $this->belongsToMany(SupplyTerm::class, 'purchase_return_supply_terms', 'purchase_return_id', 'supply_term_id');
    }

    function files()
    {
        return $this->hasMany(PurchaseReturnLibrary::class, 'purchase_return_id');
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }
}
