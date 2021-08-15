<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMaintenance extends Model
{
    use ColumnTranslation;

    protected $table = 'asset_maintenances';

    protected $fillable = [
        'name_ar',
        'name_en',
        'asset_id',
        'maintenance_detection_id',
        'maintenance_detection_type_id',
        'period',
        'number_of_km_h',
        'maintenance_type',
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
        'name' => 'name',
        'maintenance_detection_type' => 'maintenance_detection_type',
        'maintenance_detection' => 'maintenance_detection',
        'number_of_km_hour' => 'number_of_km_hour',
        'period' => 'period',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options',
    ];

    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function maintenanceDetection(): BelongsTo
    {
        return $this->belongsTo(MaintenanceDetection::class, 'maintenance_detection_id');
    }

    public function maintenanceDetectionType(): BelongsTo
    {
        return $this->belongsTo(MaintenanceDetectionType::class, 'maintenance_detection_type_id');
    }
}
