<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPurchaseOrderDetailModel extends Model
{
    use HasFactory;

    protected $table='buyer_purchase_order_detail';
 
	protected $fillable = [
        'tr_code', 'tr_date','Ac_code', 'po_code', 'style_no', 'style_no_id', 'color_id', 'size_array', 'size_qty_array','size_qty_total', 'unit_id', 'shipment_allowance','adjust_qty','remark','garment_rejection_allowance'
    ];

    protected $casts = [
        'tr_code' => 'string'
    ];


}
