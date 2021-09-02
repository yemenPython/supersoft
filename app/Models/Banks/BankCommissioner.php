<?php

namespace App\Models\Banks;

use App\Models\EmployeeData;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankCommissioner extends Model
{
    use ColumnTranslation;

    /**
     * @var string
     */
    protected $table = 'bank_commissioners';

    /**
     * @var string[]
     */
    protected $fillable = [
        'bank_data_id',
        'date_from',
        'date_to',
        'status',
        'employee_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'status' => 'status',
        'name' => 'name',
        'email' => 'email',
        'phones' => 'phones',
        'date_from' => 'date_from',
        'date_to' => 'date_to',
        'action' => 'action',
        'options' => 'options'
    ];

    public function setNameEnAttribute($value)
    {
        $this->attributes['name_en'] = is_null($value) ? $this->attributes['name_ar'] : $value;
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }
}

