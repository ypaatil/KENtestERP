<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGLocationTransferInwardDetailModel extends Model
{
    use HasFactory;

    protected $table='fg_location_transfer_inward_detail';
 
	protected $fillable = [
        'fglti_code', 'ltpki_code', 'fglti_date','main_sales_order_no','sales_order_no', 'Ac_code',  
         'substyle_id', 'fg_id', 'style_no', 'style_description','carton_no', 'color_id', 'size_array', 'rate_array', 'size_qty_array','size_qty_total', 'from_loc_id', 'to_loc_id','usedFlag','is_opening' 
    ];

    protected $casts = [
        'fglti_code' => 'string'
    ];


}
