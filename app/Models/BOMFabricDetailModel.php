<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BOMFabricDetailModel extends Model
{
    use HasFactory;

    protected $table='bom_fabric_details';
 
	protected $fillable = [
        'bom_code', 'bom_date', 'cost_type_id', 'Ac_code', 'sales_order_no', 'season_id', 
        'currency_id', 'item_code', 'class_id', 'description', 'color_id', 'consumption', 'unit_id', 'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount', 'remark','usedFlag'
    ];

    protected $casts = [
        'bom_code' => 'string'
    ];

}
