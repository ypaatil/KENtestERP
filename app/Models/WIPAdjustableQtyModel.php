<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WIPAdjustableQtyModel extends Model
{
    use HasFactory;

    protected $table='WIP_Adjustable_Qty';

    protected $primaryKey = 'WIPAQ_code';
	
	protected $fillable = [
         'WIPAQ_code', 'WIPAQ_date', 'vw_code', 'sales_order_no', 'Ac_code', 'vendorId','total_workers', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate', 'vendor_amount', 
         'narration','userId', 'delflag', 'created_at', 'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'WIPAQ_code' => 'string'
    ];



}
