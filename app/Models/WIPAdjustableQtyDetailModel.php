<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WIPAdjustableQtyDetailModel extends Model
{
    use HasFactory;

    protected $table='WIP_Adjustable_Qty_detail';
 
	protected $fillable = [
        'WIPAQ_code', 'WIPAQ_date','sales_order_no', 'Ac_code', 'vendorId','mainstyle_id',
        'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'WIPAQ_code' => 'string'
    ];


}
