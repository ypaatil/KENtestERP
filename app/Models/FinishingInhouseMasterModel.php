<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingInhouseMasterModel extends Model
{
    use HasFactory;

    protected $table='finishing_inhouse_master';

    protected $primaryKey = 'fns_code';
	
	protected $fillable = [
         'fns_code', 'fns_date','vpo_code', 'sales_order_no', 'Ac_code','process_id', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate', 'vendor_amount', 'userId', 'delflag', 'created_at', 'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'fns_code' => 'string'
    ];



}
