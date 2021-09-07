<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetInsurance extends Model
{
    use ColumnTranslation, LogsActivity;
    /**
     * @var string
     */
    protected $table = 'assets_insurances';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'insurance_details',
        'status',
        'start_date',
        'end_date',
        'asset_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static $logAttributes = [
        'insurance_details',
        'status',
        'start_date',
        'end_date',
        'asset_id',
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'status' => 'status',
        'insurance_details' => 'insurance_details',
        'start_date' => 'start_date',
        'end_date' => 'end_date',
        'action' => 'action',
        'options' => 'options'
    ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }
}
