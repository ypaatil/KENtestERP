<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGLocationTransferInwardMasterModel extends Model
{
    use HasFactory;

    protected $table='fg_location_transfer_inward_master';

    protected $primaryKey = 'fglti_code';
	
	protected $fillable = [
         'fglti_code', 'ltpki_code', 'fglti_date', 'main_sales_order_no', 'Ac_code', 
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'narration', 'from_loc_id', 'to_loc_id',  'userId', 'delflag', 'created_at',
         'updated_at','c_code'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'fglti_code' => 'string'
    ];



}
