<?php

namespace App\Models\FinancialManagement;

use App\Models\Branch;
use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeRevenue extends Model
{
    use ColumnTranslation;

    /**
     * @var string
     */
    protected $table = 'type_revenues';

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
        return $this->hasMany(TypeRevenue::class, 'parent_id');
    }
}
