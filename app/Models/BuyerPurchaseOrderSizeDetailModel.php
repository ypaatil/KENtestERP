<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPurchaseOrderSizeDetailModel extends Model
{
    use HasFactory;

    protected $table='buyer_purchase_order_size_detail';
 
	protected $fillable = [
         'tr_code', 'tr_date', 'Ac_code',   'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no','style_no_id', 'style_description','carton_no', 'color_id', 'item_code',
         'size_array', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's11', 
         's12', 's13', 's14', 's15', 's16', 's17', 's18', 's19', 's20', 'size_qty_total' , 'order_rate'
    ];

    protected $casts = [
        'tr_code' => 'string'
    ];


}
