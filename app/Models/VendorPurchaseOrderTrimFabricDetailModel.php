<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPurchaseOrderTrimFabricDetailModel extends Model
{
    use HasFactory;


    protected $table='vendor_purchase_order_trim_fabric_details';
 
	protected $fillable = [
        'vw_code', 'vw_date', 'cost_type_id','process_id', 'Ac_code', 'sales_order_no', 'season_id', 'currency_id',
        'item_code', 'class_id', 'description',     'consumption', 'unit_id',
          'wastage', 'bom_qty'  ,'actual_qty', 'final_cons', 'size_qty'
    ];

    protected $casts = [
        'vw_code' => 'string'
    ];
}
