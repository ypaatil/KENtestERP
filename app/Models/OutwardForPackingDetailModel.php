<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardForPackingDetailModel extends Model
{
    use HasFactory;

    protected $table='outward_for_packing_detail';
 
	protected $fillable = [
        'ofp_code', 'off_date','vw_code', 'sales_order_no', 'Ac_code', 'vendorId',  'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'ofp_code' => 'string'
    ];


}
 