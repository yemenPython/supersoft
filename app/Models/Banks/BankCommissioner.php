<?php

namespace App\Models\Banks;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;

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
        'branch_id',
        'bank_data_id',
        'name_ar',
        'name_en',
        'phone1',
        'phone2',
        'phone3',
        'email',
        'job',
        'date_from',
        'date_to',
        'status',
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
        'job' => 'job',
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
}

