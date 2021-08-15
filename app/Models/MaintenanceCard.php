<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceCard extends Model
{
    protected $fillable = [
        'number',
        'type',
        'branch_id',
        'asset_id',
        'created_by',
        'receive_status',
        'status',
        'receive_date',
        'receive_time',
        'delivery_status',
        'delivery_date',
        'delivery_time',
        'supplier_id',
        'note',
        'maintenance_center_id',
    ];

    protected $table = 'maintenance_cards';

    public function asset ()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function createdBy ()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function maintenanceCenter(): BelongsTo
    {
        return $this->belongsTo(MaintenanceCenter::class, 'maintenance_center_id');
    }
}
