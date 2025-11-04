<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardForFinishingMasterModel extends Model
{
    use HasFactory;

    protected $table='outward_for_finishing_master';

    protected $primaryKey = 'off_code';
	
	protected $fillable = [
         'off_code', 'off_date', 'process_id',  'sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate',
         'vendor_amount',   'userId', 'narration', 'delflag', 'created_at', 'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'off_code' => 'string'
    ];



}
