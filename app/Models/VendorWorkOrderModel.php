<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWorkOrderModel extends Model
{
    use HasFactory;

    protected $table='vendor_work_order_master';

    protected $primaryKey = 'vw_code';
	
	protected $fillable = [
         'vw_code', 'vw_date','cost_type_id','sales_order_no', 'Ac_code', 'season_id', 'currency_id','mainstyle_id',
          'substyle_id', 'fg_id', 'style_no','order_rate',   'style_description',  'narration', 'debit_reject_garment',
         'userId', 'delflag', 'created_at', 'updated_at','c_code','vendorId','cons_per_piece','vendorRate',  'final_bom_qty','delivery_date', 'endflag'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'vw_code' => 'string'
    ];



}
