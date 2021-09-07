<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetExamination extends Model
{
    use ColumnTranslation, LogsActivity;
    /**
     * @var string
     */
    protected $table = 'assets_examinations';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'examination_details',
        'start_date',
        'end_date',
        'status',
        'asset_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static $logAttributes = [
        'examination_details',
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
        'examination_details' => 'examination_details',
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
