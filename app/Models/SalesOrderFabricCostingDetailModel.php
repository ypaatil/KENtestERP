<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class SalesOrderFabricCostingDetailModel extends Model
{
    use HasFactory;

    protected $table='sales_order_fabric_costing_details';
 
	protected $fillable = [
        'soc_code', 'soc_date','cost_type_id', 'Ac_code','sales_order_no', 'season_id', 'currency_id', 'item_code', 'quality_code', 
        'count_construction', 'consumption', 'rate_per_unit', 'wastage', 'bom_qty',  'total_amount' 
    ];

    protected $casts = [
        'soc_code' => 'string'
    ];
 
}
