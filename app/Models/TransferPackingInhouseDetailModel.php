<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferPackingInhouseDetailModel extends Model
{
    use HasFactory;

    protected $table='transfer_packing_inhouse_detail';
 
	protected $fillable = [
        'tpki_code', 'tpki_date','main_sales_order_no','sales_order_no', 'Ac_code',  
         'substyle_id', 'fg_id', 'style_no', 'style_description','carton_no', 'color_id', 'size_array', 'size_qty_array','size_qty_total'  , 'order_rate', 'usedFlag'
    ];

    protected $casts = [
        'tpki_code' => 'string'
    ];


}
