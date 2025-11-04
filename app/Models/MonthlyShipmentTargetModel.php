<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyShipmentTargetModel extends Model
{
    use HasFactory;

    protected $table='monthly_shipment_target_master';
    protected $primaryKey = 'monthlyShipmentTargetMasterId';
	
	protected $fillable = [
        'sales_order_no', 'buyer_code','mainstyle_id','userId','updated_at'
    ];

}
