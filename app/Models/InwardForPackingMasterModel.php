<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardForPackingMasterModel extends Model
{
    use HasFactory;

    protected $table='inward_for_packing_master';

    protected $primaryKey = 'ifp_code';
	
	protected $fillable = [
         'ifp_code', 'ofp_code', 'ifp_date','vw_code', 'sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate',
         'vendor_amount',   'userId', 'narration', 'delflag', 'created_at', 'updated_at','c_code','sent_to',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'ifp_code' => 'string'
    ];



}
