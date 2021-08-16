<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceCenter extends Model
{
    use ColumnTranslation;

    protected $table = 'maintenance_centers';

    protected $fillable = [
        'name_en',
        'name_ar',
        'branch_id',
        'status',
        'country_id',
        'city_id',
        'area_id',
        'phone_1',
        'phone_2',
        'address',
        'email',
        'fax',
        'commercial_number',
        'tax_card',
        'tax_number',
        'lat',
        'long',
        'identity_number',
        'commercial_record_area',
        'tax_file_number',
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'name' => 'name',
        'branch' => 'branch',
        'phone' => 'phone',
        'email' => 'email',
        'commercial_number' => 'commercial_number',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        if (!authIsSuperAdmin()) {
            unset(self::$dataTableColumns['branch']);
        }
        return self::$dataTableColumns;
    }
}
