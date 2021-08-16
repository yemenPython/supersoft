<?php
namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreTransfer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transfer_number' ,'transfer_date' ,'store_from_id' ,'store_to_id' ,'branch_id','total','time','user_id', 'description',
        'library_path'
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'branch_id' => 'branch_id',
        'transfer_date' => 'transfer_date',
        'transfer_number' => 'transfer_number',
        'store_from' => 'store_from',
        'store_to' => 'store_to',
        'total' => 'total',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    function items() {
        return $this->hasMany(StoreTransferItem::class ,'store_transfer_id');
    }

    function store_from() {
        return $this->belongsTo(Store::class ,'store_from_id');
    }

    function store_to() {
        return $this->belongsTo(Store::class ,'store_to_id');
    }

    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }

    public function user () {

        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function getNumberAttribute () {

        return $this->transfer_number . '_#';
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

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

    function files()
    {
        return $this->hasMany(StoreTransferLibrary::class, 'store_transfer_id');
    }
}
