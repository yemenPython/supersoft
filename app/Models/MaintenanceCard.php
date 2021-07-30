<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceCard extends Model
{
    protected $fillable = ['number', 'type', 'branch_id', 'asset_id', 'created_by', 'receive_status', 'status',
        'receive_date', 'receive_time', 'delivery_status', 'delivery_date', 'delivery_time'];

    protected $table = 'maintenance_cards';

    public function asset ()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function createdBy ()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
