<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ConcessionExecution extends Model
{
    protected $fillable = ['concession_id','date_from','date_to','status','notes'];

    protected $table = 'concession_executions';

    public function concession () {

        return $this->belongsTo(Concession::class, 'concession_id');
    }

    public function getStartDateAttribute () {

        return Carbon::create($this->date_from)->format('Y-m-d');
    }

    public function getEndDateAttribute () {

        return Carbon::create($this->date_to)->format('Y-m-d');
    }
}
