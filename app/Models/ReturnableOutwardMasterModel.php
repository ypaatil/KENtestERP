<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnableOutwardMasterModel extends Model
{
    use HasFactory;

     protected $table='returnableoutwardmaster';
     protected $primaryKey='RetOutwardcode';

	protected $fillable = [
    'RetOutwardcode','RetOutwardDate','Ac_code','tax_type_id','dept_id','machineId','Gross_amount','Gst_amount','Net_amount','firm_id','c_code','loc_id','gstNo','address','remark','vehicalNo','termOfPayment','userId','created_at','updated_at',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];

       protected $casts = [
        'RetOutwardcode' => 'string'
    ];

}
