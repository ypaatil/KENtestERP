<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWorkOrderDetailModel extends Model
{
    use HasFactory;

    protected $table='vendor_work_order_detail';
 
	protected $fillable = [
        'vw_code', 'vw_date','Ac_code', 'po_code', 'style_no', 'color_id', 'size_array', 'size_qty_array','size_qty_total' 
    ];

    protected $casts = [
        'vw_code' => 'string'
    ];


}
