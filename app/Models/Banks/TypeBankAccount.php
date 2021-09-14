<?php

namespace App\Models\Banks;

use App\Models\Branch;
use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeBankAccount extends Model
{
    use ColumnTranslation;

    /**
     * @var string
     */
    protected static $viewPath = 'admin.banks.type_bank_accounts.';

    /**
     * @var string
     */
    protected $table = 'type_bank_accounts';

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
        return $this->hasMany(TypeBankAccount::class, 'parent_id');
    }

    public static function getMainTypes(int $branch_id = NULL, BankAccount $bankAccount = null)
    {
        $htmlCode = "<option value=''>". __('words.select-one') ."</option>";
        $startCounter = 1;
        TypeBankAccount::where('status', 1)->select()
            ->whereNull('parent_id')
            ->when($branch_id ,function ($q) use ($branch_id) {
                $q->where('branch_id' ,$branch_id);
            })
            ->get()
            ->each(function ($type) use (&$htmlCode ,&$startCounter, $bankAccount) {
                $htmlCode .= view(self::$viewPath . '.tree-option' , [
                    'child' => $type,
                    'counter' => $startCounter,
                    'bankAccount' => $bankAccount,
                ])->render();
                $startCounter++;
            });
        return $htmlCode;
    }
}
