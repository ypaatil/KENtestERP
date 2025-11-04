<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingBillingDetailModel extends Model
{
    use HasFactory;

    protected $table='finishing_billing_details'; 
	
	protected $fillable = [
        'finishing_billing_code','sales_order_no','perticular_ids','brand_id','fg_id','style_no','till_date_packing_inward_qty','till_date_packing_qty','till_date_billing_qty','packing_qty','rate','amount'
    ];
 
}
