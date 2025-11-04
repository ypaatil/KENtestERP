<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerCostingMasterModel extends Model
{
    use HasFactory;

    protected $table='buyer_costing_master';

    protected $primaryKey = 'sr_no';
	
	protected $fillable = [
            'entry_date','buyer_name','brand_name','inr_rate','exchange_rate','fob_rate', 'total_qty','total_value','style_name','style_no','style_description','sam','cur_id','og_id','fabric_value','fabric_per','sewing_trims_value','sewing_trims_per',
            'packing_trims_value','packing_trims_per','production_value','production_per','other_value','other_per','transport_value','transport_per','agent_commission_value','agent_commission_per','dbk_value',
            'dbk_per','dbk_value1','dbk_per1','printing_value','printing_per','embroidery_value','embroidery_per','ixd_value','ixd_per','total_making_value',
            'total_making_per','garment_reject_value','garment_reject_per','testing_charges_value','testing_charges_per','finance_cost_value','finance_cost_per',
            'extra_value','extra_per','total_cost_value','total_cost_per','profit_value','profit_per','narration','userId','delflag','created_at','updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];  
}
