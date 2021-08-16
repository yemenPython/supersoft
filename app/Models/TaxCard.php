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

class TaxCard extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'tax_cards';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'branch_id',
        'activity',
        'registration_number',
        'registration_date',
        'end_date',
        'library_path'
    ];

    protected static $logAttributes = [
        'branch_id',
        'activity',
        'registration_number',
        'registration_date',
        'end_date',
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
        return $this->hasMany(TaxCardLibrary::class, 'tax_card_id');
    }
}
