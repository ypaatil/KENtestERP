<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BOMMasterModel extends Model
{
    use HasFactory;

    protected $table='bom_master';

    protected $primaryKey = 'bom_code';
	
	protected $fillable = [
         'bom_code', 'bom_date','cost_type_id','sales_order_no', 'Ac_code', 'season_id', 'currency_id','mainstyle_id',
          'substyle_id', 'fg_id', 'style_no','order_rate',   'style_description','total_qty', 'fabric_value',
         'sewing_trims_value','packing_trims_value',  'total_cost_value', 'narration','is_approved',
         'userId', 'delflag', 'created_at', 'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'bom_code' => 'string'
    ];



}
