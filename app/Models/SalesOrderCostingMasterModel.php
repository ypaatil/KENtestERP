<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderCostingMasterModel extends Model
{
    use HasFactory;

    protected $table='sales_order_costing_master';

    protected $primaryKey = 'soc_code';
	
	protected $fillable = [
         'soc_code', 
         'soc_date',
         'cost_type_id',
         'sales_order_no', 
         'Ac_code', 
         'season_id',
         'brand_id',
         'currency_id',
         'mainstyle_id', 
         'substyle_id', 
         'fg_id',
         'style_no',
         'style_description', 
         'order_rate', 
         'exchange_rate',
         'inr_rate',
         'sam',
         'transport_ocr_cost',
         'testing_ocr_cost', 
         'fabric_value',
         'sewing_trims_value',
         'packing_trims_value', 
         'production_value', 
         'transaport_value', 
         'other_value',
         'agent_commision_value',
         'dbk_value', 
         'printing_value', 
         'embroidery_value', 
         'ixd_value',
         'garment_reject_value',
         'testing_charges_value',
         'finance_cost_value', 
         'extra_value', 
         'total_cost_value',
         'narration', 
         'is_approved',
         'userId', 
         'delflag', 
         'c_code',
         'PDMerchant_id',
         'dbk_value1',
         'total_making_value',
         'total_making_per'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'soc_code' => 'string'
    ];



}
