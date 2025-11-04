<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingDetailModel extends Model
{
    use HasFactory;

    protected $table='packing_detail';
 
	protected $fillable = [
        'pki_code', 'pki_date','vpo_code','sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id' ,
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 
         'size_qty_array','size_qty_total' ,'vendor_rate','is_opening' ,'is_transfered','trans_sales_order_no', 'transfer_code','location_id'
    ];

    protected $casts = [
        'pki_code' => 'string'
    ];


}
