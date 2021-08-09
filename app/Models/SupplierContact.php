<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierContact extends Model
{
    protected $fillable = [
        'supplier_id',
        'phone_1',
        'phone_2',
        'address',
        'name',
        'start_date',
        'end_date',
        'email',
        'job_title',
        'status',
    ];

    protected $table = 'supplier_contacts';

    public function supplier()
    {

        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'status' => 'status',
        'name' => 'name',
        'job_title' => 'job_title',
        'phone1' => 'phone1',
        'phone2' => 'phone2',
        'address' => 'address',
        'email' => 'email',
        'start_date' => 'start_date',
        'end_date' => 'end_date',
        'action' => 'action',
        'options' => 'options',
    ];

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }
}
