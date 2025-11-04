<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocTransferPackingInhouseDetailModel extends Model
{
    use HasFactory;

    protected $table='loc_transfer_packing_inhouse_detail';
 
	protected $fillable = [
        'ltpki_code', 'ltpki_date','main_sales_order_no','sales_order_no', 'Ac_code',  
         'substyle_id', 'fg_id', 'style_no', 'style_description','carton_no', 'color_id', 'size_array', 'size_qty_array','size_qty_total', 'from_loc_id', 'to_loc_id','usedFlag'
    ];

    protected $casts = [
        'ltpki_code' => 'string'
    ];


}
