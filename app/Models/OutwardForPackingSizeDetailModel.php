<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardForPackingSizeDetailModel extends Model
{
    use HasFactory;

    protected $table='outward_for_packing_size_detail';
 
	protected $fillable = [
         'ofp_code', 'ofp_date', 'vw_code', 'Ac_code', 'sales_order_no', 'vendorId', 'mainstyle_id','substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'item_code','size_array','size_qty_array', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's11', 's12', 's13', 's14', 's15', 's16', 's17', 's18', 's19', 's20', 'size_qty_total' 
    ];

    protected $casts = [
        'ofp_code' => 'string'
    ];


}
