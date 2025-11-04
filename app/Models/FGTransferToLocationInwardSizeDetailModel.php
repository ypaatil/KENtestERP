<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGTransferToLocationInwardSizeDetailModel extends Model
{
    use HasFactory;

    protected $table='fg_trasnfer_to_location_inward_size_detail';
 
	protected $fillable = [
         'fgti_code', 'fgti_date', 'Ac_code', 'sales_order_no', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description','carton_no', 'color_id', 'item_code',
         'size_array', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's11', 
         's12', 's13', 's14', 's15', 's16', 's17', 's18', 's19', 's20', 'size_qty_total' , 'order_rate'
    ];

    protected $casts = [
        'fgti_code' => 'string'
    ];


}
