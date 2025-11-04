<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPackingInhouseMasterModel extends Model
{
    use HasFactory;

    protected $table='return_packing_inhouse_master';

    protected $primaryKey = 'rpki_code';
	
	protected $fillable = [
         'rpki_code', 'rpki_date','sale_code', 'sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id','tax_type_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate', 'vendor_amount', 
         'userId', 'delflag', 'created_at', 'updated_at','c_code','is_opening', 'rate','location_id','narration'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'rpki_code' => 'string'
    ];



}
