<?php
namespace App\Http\Controllers\DataExportCore\Invoices;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DataExportCore\ServerSideExport;

class PurchaseReturn {
    protected $view_path;
    protected $query_builder;
    protected $columns_definitions;
    protected $hidden_columns;
    protected $columns;
    protected $header;

    function __construct($query_builder ,$visible_columns) {
        $this->view_path = '';
        $this->columns_definitions = [
            'invoice_number' => 'invoice_number',
            'supplier' => 'supplier',
            'invoice_type' => 'invoice_type',

            'total' => 'total',
            'paid' => 'paid',
            'remaining' => 'remaining',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
        $this->columns = [
            'invoice_number' ,'supplier', 'invoice_type','total','paid', 'remaining', 'created_at', 'updated_at'
        ];
        $this->header = [
            'invoice_number' => __('Invoice Number'),
            'supplier' => __('Supplier Name'),
            'invoice_type' => __('Invoice Type'),

            'total' => __('Total'),
            'paid' => __('Paid'),
            'remaining' => __('Remaining'),
            'created_at' => __('created at'),
            'updated_at' => __('Updated at')
        ];
        $this->query_builder = $query_builder;
        $visible_columns = explode(',' ,$visible_columns);
        foreach($visible_columns as $col) {
            if (!$col) continue;
            $this->hidden_columns[] = $this->columns_definitions[implode("_" ,explode("-" ,$col))];
        }
    }

    function __invoke($action_for) {
        $action_for = in_array($action_for ,['print' ,'excel']) ? $action_for : 'print';
        return $this->$action_for();
    }

    static function get_my_view_columns() {
        return [
            'invoice-number' => __('Invoice Number'),
            'invoice-type' => __('Invoice Type'),
            'total' => __('Total'),
            'paid' => __('Paid'),
            'remaining' => __('Remaining'),
            'supplier' => __('Supplier'),
        ];
    }

    private function print() {
        $columns_keys = [];
        if ($this->hidden_columns)
            foreach($this->columns as $col) {
                if (!in_array($col ,$this->hidden_columns)) $columns_keys[] = $col;
            }
        else $columns_keys = $this->columns;
        return view('server-side-print' ,[
            'collection' => $this->query_builder,
            'columns_keys' => $columns_keys,
            'header' => $this->header,
            'title' => __('Purchase Return Invoices Printing'),
            'custom_echo' => function ($columns_keys ,$record) {
                echo '<tr>';
                    foreach ($columns_keys as $key) {
                        switch ($key) {
                            case 'supplier':
                                $value = optional($record->supplier)->name;
                                break;
                            case 'invoice_type':
                                $value = __($record->type);
                                break;
                            case 'payment':
                                $value = $record->remaining == 0 ? __('Completed') : __('Not Completed');
                                break;
                            case 'paid':
                                $value = number_format($record->paid, 2);
                                break;
                            case 'remaining':
                                $value = number_format($record->remaining, 2);
                                break;
                            default:
                                $value = $record[$key];
                                break;
                        }
                        echo "<td class='text-center'> $value </td>";
                    }
                echo '</tr>';
            }
        ]);
    }

    private function excel() {
        $columns_keys = [];
        if ($this->hidden_columns)
            foreach($this->columns as $col) {
                if (!in_array($col ,$this->hidden_columns)) $columns_keys[] = $col;
            }
        else $columns_keys = $this->columns;
        ob_end_clean();
        ob_start();
        $export_object = new ServerSideExport($this->query_builder ,$columns_keys ,$this->header);
        $export_object->set_custom_map(function ($columns ,$record) {
            $data = [];
            foreach ($columns as $col) {
                switch ($col) {
                    case 'supplier':
                        $value = optional($record->supplier)->name;
                        break;
                    case 'invoice_type':
                        $value = __($record->type);
                        break;
                    case 'payment':
                        $value = $record->remaining == 0 ? __('Completed') : __('Not Completed');
                        break;
                    case 'paid':
                        $value = number_format($record->paid, 2);
                        break;
                    case 'remaining':
                        $value = number_format($record->remaining, 2);
                        break;
                    default:
                        $value = $record->$col;
                        break;
                }
                $data[] = $value;
            }
            return $data;
        });
        return Excel::download($export_object, 'Purchase-Return-Invoices-'.date('YmdHis').'.csv');
    }
}
