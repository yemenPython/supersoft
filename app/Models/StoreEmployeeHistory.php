<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StoreEmployeeHistory extends Model
{
    /**
     * @var string
     */
    protected $table = 'store_employee_histories';

    /**
     * @var string[]
     */
    protected $fillable = [
        'store_id',
        'employee_id',
        'start',
        'end',
        'status',
    ];

    protected $casts = [
      'status' => 'boolean'
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'status' => 'status',
        'name' => 'name',
        'phone1' => 'phone1',
        'start' => 'start',
        'end' => 'end',
        'action' => 'action',
        'options' => 'options'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }


    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
//        if (!authIsSuperAdmin()) {
//            unset(self::$dataTableColumns['branch_id']);
//        }
        return self::$dataTableColumns;
    }
}
