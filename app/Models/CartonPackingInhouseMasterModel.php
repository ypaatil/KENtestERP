<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartonPackingInhouseMasterModel extends Model
{
    use HasFactory;

    protected $table='carton_packing_inhouse_master';

    protected $primaryKey = 'cpki_code';
	
	protected $fillable = [
         'cpki_code', 'cpki_date','firm_id', 'sales_order_no', 'Ac_code', 
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'order_rate', 'order_amount', 
         'narration','buyer_location_id', 'userId', 'delflag', 'created_at', 'updated_at','c_code', 'endflag','isRTV'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'cpki_code' => 'string'
    ];



}
