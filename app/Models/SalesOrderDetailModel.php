<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetailModel extends Model
{
    use HasFactory;


    protected $table='sales_order_detail';
 
	protected $fillable = [
        'tr_code', 'tr_date','Ac_code', 'po_code', 'style_no', 'color_id', 'size_array',  
        's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's12', 's13', 's14',
         's15', 's16', 's17', 's18', 's19', 's20',
        'size_qty_total', 'unit_id', 'shipment_allowance','adjust_qty','remark','garment_rejection_allowance'
    ];

    protected $casts = [
        'tr_code' => 'string'
    ];
}
