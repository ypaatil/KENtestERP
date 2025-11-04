<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingVendorMasterModel extends Model
{
    use HasFactory;

    protected $table='packing_vendor_master';

    protected $primaryKey = 'vpki_code';
	
	protected $fillable = [
         'vpki_code', 'vpki_date','vpo_code', 'sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate', 'vendor_amount', 
         'userId', 'delflag', 'created_at', 'updated_at','c_code','is_opening', 'rate' 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'vpki_code' => 'string'
    ];



}
