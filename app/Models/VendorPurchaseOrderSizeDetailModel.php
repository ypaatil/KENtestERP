<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPurchaseOrderSizeDetailModel extends Model
{
    use HasFactory;


    protected $table='vendor_purchase_order_size_detail';
 
	protected $fillable = [
        'vpo_code', 'vpo_date','process_id','Ac_code','sales_order_no', 'po_code', 'style_no', 'color_id', 'size_array',  
        's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's12', 's13', 's14',
         's15', 's16', 's17', 's18', 's19', 's20',
        'size_qty_total' 
    ];

    protected $casts = [
        'vpo_code' => 'string'
    ];
}
