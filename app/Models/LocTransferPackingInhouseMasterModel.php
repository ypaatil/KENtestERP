<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocTransferPackingInhouseMasterModel extends Model
{
    use HasFactory;

    protected $table='loc_transfer_packing_inhouse_master';

    protected $primaryKey = 'ltpki_code';
	
	protected $fillable = [
         'ltpki_code', 'ltpki_date','firm_id', 'main_sales_order_no', 'Ac_code', 
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'narration', 'from_loc_id', 'to_loc_id',  'userId', 'delflag', 'created_at',
         'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'ltpki_code' => 'string'
    ];



}
