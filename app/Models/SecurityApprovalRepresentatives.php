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

class SecurityApprovalRepresentatives extends Model
{
    protected $table = 'security_approval_representatives';

    protected $fillable = [
        'security_approval_id',
        'employee_id'
    ];
    public function employee()
    {
        return $this->belongsTo( EmployeeData::class, 'employee_id' );
    }
}
