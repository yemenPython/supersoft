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

class SecurityApproval extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'security_approval';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'branch_id',
        'user_id',
        'contract_date',
        'register_date',
        'commercial_feature',
        'company_purpose',
        'share_capital',
        'partnership_duration',
        'start_at',
        'end_at',
        'library_path'
    ];

    protected static $logAttributes = [
        'branch_id',
        'contract_date',
        'register_date',
        'commercial_feature',
        'company_purpose',
        'share_capital',
        'partnership_duration',
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
        return $this->hasMany(CompanyContractLibrary::class, 'company_contract_id');
    }
    public function owners()
    {
        return $this->hasMany(CompanyContractPartners::class,'company_contract_id');
    }
    public function representatives()
    {
        return $this->hasMany(CompanyContractCompanyShare::class,'company_contract_id');
    }
    public function phones()
    {
        return $this->hasMany(CompanyContractCompanyShare::class,'company_contract_id');
    }
}
