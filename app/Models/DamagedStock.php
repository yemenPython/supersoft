<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DamagedStock extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['branch_id', 'user_id', 'number', 'date', 'time', 'total', 'description', 'type', 'library_path'];

    protected $table = 'damaged_stocks';

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'branch_id' => 'branch_id',
        'date' => 'date',
        'number' => 'number',
        'type' => 'type',
        'total' => 'total',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getSpecialNumberAttribute()
    {
        return $this->number . '_#';
    }

    public function items()
    {
        return $this->hasMany(DamagedStockItem::class, 'damaged_stock_id');
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }

    public function employees () {

        return $this->belongsToMany(EmployeeData::class, 'damaged_stock_employee_data', 'damaged_stock_id', 'employee_data_id')
            ->withPivot('id','percent', 'amount');
    }

    function files()
    {
        return $this->hasMany(DamagedStockLibrary::class, 'damaged_stock_id');
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
}
