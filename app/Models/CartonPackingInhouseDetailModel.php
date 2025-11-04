<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartonPackingInhouseDetailModel extends Model
{
    use HasFactory;

    protected $table='carton_packing_inhouse_detail';
 
	protected $fillable = [
        'cpki_code', 'cpki_date','sales_order_no', 'Ac_code',  
         'substyle_id', 'fg_id', 'style_no','style_no_id', 'style_description','carton_no', 'color_id', 'size_array', 'size_qty_array','size_qty_total'  , 'order_rate'
    ];

    protected $casts = [
        'cpki_code' => 'string'
    ];


}
