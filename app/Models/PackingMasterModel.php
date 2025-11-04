<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingMasterModel extends Model
{
    use HasFactory;

    protected $table='packing_master';

    protected $primaryKey = 'pki_code';
	
	protected $fillable = [
         'pki_code', 'pki_date','vpo_code', 'sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate', 'vendor_amount', 
         'userId', 'delflag', 'created_at', 'updated_at','c_code','is_opening', 'rate','location_id','packing_type_id','narration'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'pki_code' => 'string'
    ];



}
