<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardForPackingMasterModel extends Model
{
    use HasFactory;

    protected $table='outward_for_packing_master';

    protected $primaryKey = 'ofp_code';
	
	protected $fillable = [
         'ofp_code', 'ofp_date','vw_code', 'sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate',
         'vendor_amount',   'userId', 'narration', 'delflag', 'created_at', 'updated_at','c_code','sent_to',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'ofp_code' => 'string'
    ];



}
