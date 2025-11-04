<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPurchaseOrderModel extends Model
{
    use HasFactory;

    protected $table='vendor_purchase_order_master';

    protected $primaryKey = 'vpo_code';
	
	protected $fillable = [
         'vpo_code', 'vpo_date', 'delivery_date','cost_type_id','process_id','sales_order_no', 'Ac_code', 'season_id', 'currency_id','mainstyle_id',
          'substyle_id', 'fg_id', 'style_no','order_rate',   'style_description',  'final_bom_qty',
         'userId', 'delflag', 'created_at', 'updated_at','c_code','vendorId','endflag','line_id'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'vpo_code' => 'string'
    ];



}
