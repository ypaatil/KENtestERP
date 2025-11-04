<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartonPackingInhouseSizeDetailModel extends Model
{
    use HasFactory;

    protected $table='carton_packing_inhouse_size_detail';
 
	protected $fillable = [
         'cpki_code', 'cpki_date',   'Ac_code', 'sales_order_no',   'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no','style_no_id', 'style_description','carton_no', 'color_id', 'item_code',
         'size_array', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's11', 
         's12', 's13', 's14', 's15', 's16', 's17', 's18', 's19', 's20', 'size_qty_total' , 'order_rate'
    ];

    protected $casts = [
        'cpki_code' => 'string'
    ];


}
