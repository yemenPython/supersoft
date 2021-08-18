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

class RegisterAddedValue extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'register_added_value';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'branch_id',
        'area',
        'register_date',
        'errands',
        'library_path'
    ];

    protected static $logAttributes = [
        'branch_id',
        'area',
        'register_date',
        'errands',
        'library_path'
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
        return $this->hasMany(RegisterAddedValueLibrary::class, 'register_added_value_id');
    }
}
