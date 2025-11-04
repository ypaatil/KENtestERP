<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QCStitchingInhouseMasterModel extends Model
{
    use HasFactory;

    protected $table='qcstitching_inhouse_master';

    protected $primaryKey = 'qcsti_code';
	
	protected $fillable = [
         'qcsti_code', 'qcsti_date', 'sales_order_no', 'Ac_code', 'vendorId','line_id', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate', 'vendor_amount', 'userId', 'delflag', 'created_at', 'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'qcsti_code' => 'string'
    ];



}
