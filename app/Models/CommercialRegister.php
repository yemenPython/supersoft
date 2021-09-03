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

class CommercialRegister extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'commercial_register';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'branch_id',
        'commercial_registry_office',
        'national_number',
        'deposit_number',
        'deposit_date',
        'valid_until',
        'commercial_feature',
        'company_type',
        'purpose',
        'no_of_years',
        'start_at',
        'end_at',
        'renewable'
    ];

    protected static $logAttributes = [
        'branch_id',
        'commercial_registry_office',
        'national_number',
        'deposit_number',
        'deposit_date',
        'valid_until',
        'commercial_feature',
        'company_type',
        'purpose',
        'no_of_years',
        'start_at',
        'end_at',
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
        return $this->hasMany(CommercialRegisterLibrary::class, 'commercial_register_id');
    }
}
