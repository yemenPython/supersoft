<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class EgyptianFederationOfConstructionAndBuildingContractors extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'egyptian_federation_of_construction_and_building_contractors';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'branch_id',
        'membership_no',
        'date_of_register',
        'payment_receipt',
        'end_date',
        'company_type',
    ];

    protected static $logAttributes = [
        'branch_id',
        'membership_no',
        'date_of_register',
        'payment_receipt',
        'end_date',
        'company_type',
     ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }
    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }
    public function files()
    {
        return $this->hasMany(EgyptianFederationLibrary::class, 'egyptian_federation_id');
    }
}
