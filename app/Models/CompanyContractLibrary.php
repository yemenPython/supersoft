<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class CompanyContractLibrary extends Model
{
    protected $fillable = ['company_contract_id','file_name', 'extension', 'name','title_en','title_ar'];

    protected $table = 'company_contract_libraries';

    public function company_contract() {

        return $this->belongsTo(CompanyContract::class, 'company_contract_id');
    }
    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
