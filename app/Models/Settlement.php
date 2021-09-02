<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settlement extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['branch_id', 'user_id', 'number', 'date', 'time', 'total', 'description', 'type', 'library_path'];

    protected $table = 'settlements';

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
        return $this->number;
    }

    public function items()
    {
        return $this->hasMany(SettlementItem::class, 'settlement_id');
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }

    function files()
    {
        return $this->hasMany(SettlementLibrary::class, 'settlement_id');
    }

    function employees()
    {
        return $this->belongsToMany(EmployeeData::class, 'employee_settlements', 'settlement_id', 'employee_id');
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
