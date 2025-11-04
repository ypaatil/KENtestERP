<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedPcsDeliveryChallanModel extends Model
{
    use HasFactory;

    protected $table='rejected_pcs_delivery_challan';

    protected $primaryKey = 'rpdc_code';
	
	protected $fillable = [
         'rpdc_code', 'rpdc_date','firm_id', 'sales_order_no', 'vendorId', 'total_qty', 'total_amount', 'userId', 'delflag', 'created_at', 'updated_at','narration'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'rpdc_code' => 'string'
    ];



}
