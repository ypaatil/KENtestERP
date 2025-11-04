<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingVendorDetailModel extends Model
{
    use HasFactory;

    protected $table='packing_vendor_detail';
 
	protected $fillable = [
        'vpki_code', 'vpki_date','vpo_code','sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id' ,
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 
         'size_qty_array','size_qty_total' ,'vendor_rate','is_opening'  
    ];

    protected $casts = [
        'vpki_code' => 'string'
    ];


}
