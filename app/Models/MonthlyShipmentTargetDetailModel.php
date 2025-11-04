<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyShipmentTargetDetailModel extends Model
{
    use HasFactory;

    protected $table='monthly_shipment_target_detail';
    protected $primaryKey = 'monthlyShipmentTargetDetailId';
	
	protected $fillable = [
        'monthlyShipmentTargetMasterId','sales_order_no','buyer_code','mainstyle_id','po_code','brand_id','fg_id','sam','order_value', 'fromDate','fromDate','week1', 'week2', 'week3', 'week4', 'targetQty', 'orderRate', 'value','userId','updated_at','monthDate'
    ];

}
