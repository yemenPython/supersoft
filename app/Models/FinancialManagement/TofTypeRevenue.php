<?php

namespace App\Models\FinancialManagement;

use App\Models\Branch;
use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TofTypeRevenue extends Model
{
    use ColumnTranslation;

    /**
     * @var string
     */
    protected $table = 'tof_type_revenues';

    /**
     * @var string[]
     */
    protected $fillable = [
        'branch_id',
        'name_ar',
        'name_en',
        'parent_id',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(TofTypeRevenue::class, 'parent_id');
    }
}
