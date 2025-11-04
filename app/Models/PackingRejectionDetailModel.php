<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingRejectionDetailModel extends Model
{
    use HasFactory;

    protected $table='packing_rejection_detail';
 
	protected $fillable = [
        'qcp_code', 'qcp_date','vpo_code','sales_order_no', 'Ac_code', 'vendorId','mainstyle_id','vpo_code',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'qcp_code' => 'string'
    ];


}
