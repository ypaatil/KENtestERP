<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPurchaseOrderFabricDetailModel extends Model
{
    use HasFactory;

    protected $table='vendor_purchase_order_fabric_details';
 
	protected $fillable = [
        'vpo_code', 'vpo_date', 'cost_type_id','process_id', 'Ac_code', 'sales_order_no', 'season_id', 
        'currency_id', 'item_code', 'class_id', 'description', 'color_id', 'consumption', 'unit_id',  'wastage', 'bom_qty'  ,'actual_qty', 'final_cons', 'size_qty'
    ];

    protected $casts = [
        'vpo_code' => 'string'
    ];

}
