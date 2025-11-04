<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGTransferToLocationInwardDetailModel extends Model
{
    use HasFactory;

    protected $table='fg_trasnfer_to_location_inward_detail';
 
	protected $fillable = [
        'fgti_code', 'fgti_date','sales_order_no', 'Ac_code',  
         'substyle_id', 'fg_id', 'style_no', 'style_description','carton_no', 'color_id', 'size_array', 'size_qty_array','size_qty_total', 'order_rate'
    ];

    protected $casts = [
        'fgti_code' => 'string'
    ];


}
