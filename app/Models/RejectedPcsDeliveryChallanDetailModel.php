<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedPcsDeliveryChallanDetailModel extends Model
{
    use HasFactory;

    protected $table='rejected_pcs_delivery_challan_detail';

	
	protected $fillable = [
         'rpdc_code', 'rpdc_date','firm_id', 'sales_order_no', 'vendorId',  'total_qty', 'total_amount', 'userId', 'delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];


}
