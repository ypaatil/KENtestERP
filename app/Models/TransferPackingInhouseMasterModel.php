<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferPackingInhouseMasterModel extends Model
{
    use HasFactory;

    protected $table='transfer_packing_inhouse_master';

    protected $primaryKey = 'tpki_code';
	
	protected $fillable = [
         'tpki_code', 'tpki_date','firm_id', 'main_sales_order_no', 'Ac_code', 
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'order_rate',
         'order_amount',  'narration',  'userId', 'delflag', 'created_at',
         'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'tpki_code' => 'string'
    ];



}
