<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingRejectionMasterModel extends Model
{
    use HasFactory;

    protected $table='packing_rejection_master';

    protected $primaryKey = 'qcp_code';
	
	protected $fillable = [
         'qcp_code', 'qcp_date', 'sales_order_no', 'Ac_code', 'vendorId', 'vpo_code', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate', 'vendor_amount', 
         'narration','userId', 'delflag', 'created_at', 'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'qcp_code' => 'string'
    ];



}
