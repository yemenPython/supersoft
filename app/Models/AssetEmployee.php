<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetEmployee extends Model
{
    use ColumnTranslation, LogsActivity;

    /**
     * @var string
     */
    protected $table = 'assets_employees';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'status',
        'asset_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static $logAttributes = [
        'employee_name',
        'phone',
        'start_date',
        'end_date',
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'status' => 'status',
        'name' => 'name',
        'phone' => 'phone',
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

    function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }
}
