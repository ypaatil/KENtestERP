<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPackingCostingDetailModel extends Model
{
    use HasFactory;

    protected $table='packing_buyer_costing_details';
 
	protected $fillable = [
         'sr_no', 'item_name', 'entry_date', 'consumption', 'rate_per_unit', 'wastage', 'total_amount'
    ]; 

}
