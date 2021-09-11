<?php

namespace App\Models\Banks;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use ColumnTranslation;

    /**
     * @var string
     */
    protected $table = 'bank_data';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'short_name_ar',
        'short_name_en',
        'branch',
        'code',
        'swift_code',
        'address',
        'long',
        'lat',
        'phone',
        'website',
        'url',
        'date',
        'status',
        'library_path',
        'branch_id',
        'country_id',
        'city_id',
        'area_id',
        'stop_date',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public static $rules = [
        'name_ar' => 'required|string|max:200',
        'website' => 'nullable|string|url',
        'url' => 'nullable|string|url',
        'short_name_ar' => 'nullable|max:200|string',
        'short_name_en' => 'nullable|max:200|string',
        'branch' => 'nullable|max:200|string',
        'code' => 'nullable|max:200|string',
        'swift_code' => 'nullable|max:200|string',
        'address' => 'nullable|max:200|string',
        'long' => 'nullable|max:200|numeric',
        'lat' => 'nullable|max:200|numeric',
        'phone' => 'nullable|max:200|string',
        'date' => 'required',
        'status' => 'in:1,0',
        'branch_id' => 'required|exists:branches,id',
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'name' => 'name',
        'branch' => 'branch',
        'code' => 'code',
        'swift_code' => 'swift_code',
        'date' => 'date',
        'stop_date' => 'stop_date',
        'status' => 'status',
        'action' => 'action',
        'options' => 'options'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

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
