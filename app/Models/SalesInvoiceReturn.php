<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesInvoiceReturn extends Model
{
    use  LogsActivity;

//    protected $dates = ['deleted_at'];

    protected $fillable = ['number', 'branch_id', 'created_by', 'date', 'time', 'type',
        'discount_type', 'discount', 'tax', 'sub_total', 'total_after_discount', 'total', 'customer_discount_status'
        , 'customer_discount', 'customer_discount_type', 'additional_payments', 'library_path', 'status', 'invoice_type',
        'invoiceable_id', 'invoiceable_type', 'clientable_id', 'clientable_type'
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'date' => 'date',
        'branch_id' => 'branch_id',
        'number' => 'number',
        'type' => 'type',
        'clientable_type' => 'clientable_type',
        'clientable_id' => 'clientable_id',
        'total' => 'total',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        if (!authIsSuperAdmin()) {
            unset(self::$dataTableColumns['branch_id']);
        }
        return self::$dataTableColumns;
    }

    protected $table = 'sales_invoice_returns';

    protected static $logAttributes = ['number', 'created_by', 'type', 'discount_type', 'total_after_discount',
        'sub_total', 'total', 'customer_discount_status'];

    protected static $logOnlyDirty = true;

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

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function getInvNumberAttribute()
    {
        return '##_' . $this->number;
    }

    public function expensesReceipts()
    {
        return $this->hasMany(ExpensesReceipt::class, 'sales_invoice_return_id');
    }

    public function getPaidAttribute()
    {
        return $this->expensesReceipts->sum('cost');
    }

    public function getRemainingAttribute()
    {
        return $this->total - $this->expensesReceipts->sum('cost');
    }

    public function items()
    {
        return $this->hasMany(SalesInvoiceItemReturn::class, 'sales_invoice_return_id');
    }

//    public function delete()
//    {
//        DB::transaction(function()
//        {
//            foreach ($this->items()->get() as $sales_item) {
//
//                $part = $sales_item->part;
//
//                if($part){
//                    $part->quantity -= $sales_item->return_qty;
//                    $part->save();
//                }
//
//                $purchase_invoice = $sales_item->purchaseInvoice;
//
//                if($purchase_invoice){
//
//                    $purchase_item = $purchase_invoice->items()->where('part_id', $sales_item->part_id)->first();
//
//                    if($purchase_item){
//                        $purchase_item->purchase_qty -= $sales_item->return_qty;
//                        $purchase_item->save();
//                    }
//                }
//            }
//
//            $this->items()->delete();
//            $this->removeBulkBalance($this->expensesReceipts()->get());
//
//            if($this->expensesReceipts){
//
//                $this->expensesReceipts()->delete();
//            }
//
//            parent::delete();
//        });
//    }

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
        $locker = Locker::findOrFail($receipt->locker_id);
        $locker->update([
            'balance' => ($locker->balance + $receipt->cost)
        ]);
    }

    public function removeBalanceFromAccount(ExpensesReceipt $receipt)
    {
        $account = Account::findOrFail($receipt->account_id);
        $account->update([
            'balance' => ($account->balance + $receipt->cost)
        ]);
    }

//    public function pointsLogs () {
//
//        return $this->hasMany(PointLog::class, 'sales_invoice_return_id');
//    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'sales_invoice_return_taxes_fees',
            'sales_invoice_return_id', 'tax_id');
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function clientable()
    {
        return $this->morphTo();
    }

    //    public function terms()
//    {
//        return $this->belongsToMany(SupplyTerm::class, 'sales_invoice_supply_terms', 'sales_invoice_id', 'supply_term_id');
//    }

    function files()
    {
        return $this->hasMany(SalesInvoiceReturnLibrary::class, 'sales_return_id');
    }

}
