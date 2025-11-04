<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPurchaseOrderDetailModel extends Model
{
    use HasFactory;

    protected $table='vendor_purchase_order_detail';
 
	protected $fillable = [
        'vpo_code', 'vpo_date','process_id', 'Ac_code', 'po_code', 'style_no', 'color_id', 'size_array', 'size_qty_array','size_qty_total' 
    ];

    protected $casts = [
        'vpo_code' => 'string'
    ];


}
