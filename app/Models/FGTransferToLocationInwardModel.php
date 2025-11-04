<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGTransferToLocationInwardModel extends Model
{
    use HasFactory;

    protected $table='fg_trasnfer_to_location_inward_master';

    protected $primaryKey = 'fgti_code';
	
	protected $fillable = [
         'fgti_code', 'fgt_code','pki_code', 'fgti_date','firm_id', 'sales_order_no', 'Ac_code', 
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'order_rate', 'order_amount', 
         'narration','buyer_location_id', 'userId', 'delflag', 'created_at', 'updated_at','c_code', 'endflag','isRTV','from_loc_id','to_loc_id','driver_name','vehical_no'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'fgti_code' => 'string'
    ];



}
