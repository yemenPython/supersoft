<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SecurityApprovalLibrary extends Model
{
    protected $fillable = ['security_approval_id','file_name', 'extension', 'name','title_en','title_ar'];

    protected $table = 'security_approval_libraries';

    public function security_approval() {

        return $this->belongsTo(SecurityApproval::class, 'security_approval_id');
    }
    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
