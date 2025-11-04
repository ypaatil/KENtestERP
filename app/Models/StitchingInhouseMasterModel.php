<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StitchingInhouseMasterModel extends Model
{
    use HasFactory;

    protected $table='stitching_inhouse_master';

    protected $primaryKey = 'sti_code';
	
	protected $fillable = [
         'sti_code', 'sti_date', 'sales_order_no', 'Ac_code', 'vendorId','line_id','total_workers', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate', 'vendor_amount', 
         'narration','userId', 'delflag', 'created_at', 'updated_at','c_code', 'total_helpers',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'sti_code' => 'string'
    ];



}
