<?php

namespace App\Models;

use App\Model\PurchaseReturn;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseInvoice extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'branch_id',
        'date',
        'time',
        'type',
        'number_of_items',
        'discount_type',
        'discount',
        'total',
        'total_after_discount',
        'discount_group_value',
        'is_discount_group_added',
        'paid',
        'remaining',
        'is_returned',
        'discount_group_type',
        'tax',
        'subtotal',
        'is_opening_balance',
        'supply_order_id',
        'invoice_type',
        'additional_payments',
        'status',
        'library_path'
    ];

    protected $table = 'purchase_invoices';

    protected static $logAttributes = ['invoice_number','date','time','number_of_items','total_after_discount',
        'is_discount_group_added'];

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
        'status' => 'status',
        'executionStatus' => 'executionStatus',
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
        return $this->hasMany(PurchaseInvoiceItem::class, 'purchase_invoice_id');
    }

    public function expenseReceipt(): HasMany
    {
        return $this->hasMany(ExpensesReceipt::class, 'purchase_invoice_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'purchase_invoice_taxes_fees');
    }

    public function deletePurchaseInvoice()
    {
        DB::transaction(function () {

            foreach ($this->items()->get() as $item) {

                $part = Part::find($item->part_id);

                if($part) {

                    $part->quantity -= $item->purchase_qty;

                    if($part->quantity < 0)
                        $part->quantity = 0;

                    $part->save();
                }

                $item->taxes()->detach();
            }

            $this->items()->delete();
            $this->removeBulkBalance($this->expenseReceipt()->get());
            $this->expenseReceipt()->delete();
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

    public function removeBalanceFromLocker(ExpensesReceipt $receipt)
    {
        $locker = Locker::find($receipt->locker_id);
        if ($locker) {
            $locker->update([
                'balance' => ($locker->balance + $receipt->cost)
            ]);
        }
    }

    public function removeBalanceFromAccount(ExpensesReceipt $receipt)
    {
        $account = Account::find($receipt->account_id);
        if ($account) {
            $account->update([
                'balance' => ($account->balance + $receipt->cost)
            ]);
        }
    }

    public function invoiceReturn(): HasOne
    {
        return $this->hasOne(PurchaseReturn::class);
    }

    public function getPaidAttribute(){
        return $this->expenseReceipt->sum('cost');
    }

    public function getRemainingAttribute(){
        return $this->total - $this->expenseReceipt->sum('cost');
    }

    public function execution()
    {
        return $this->hasOne(PurchaseInvoiceExecution::class, 'purchase_invoice_id');
    }

    public function files()
    {
        return $this->hasMany(PurchaseInvoiceLibrary::class, 'purchase_invoice_id');
    }

    public function purchaseReceipts()
    {
        return $this->belongsToMany(PurchaseReceipt::class, 'purchase_invoice_purchase_receipts',
            'purchase_invoice_id', 'purchase_receipt_id');
    }

    public function terms()
    {
        return $this->belongsToMany(SupplyTerm::class, 'purchase_invoice_supply_terms', 'purchase_invoice_id', 'supply_term_id');
    }

    public function supplyOrder ()
    {
        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }
}
