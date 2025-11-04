<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardForFinishingSizeDetailModel extends Model
{
    use HasFactory;

    protected $table='outward_for_finishing_size_detail';
 
	protected $fillable = [
         'off_code', 'off_date', 'vpo_code', 'Ac_code','process_id', 'sales_order_no', 'vendorId',    'mainstyle_id','substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'item_code','size_array', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's11', 's12', 's13', 's14', 's15', 's16', 's17', 's18', 's19', 's20', 'size_qty_total' 
    ];

    protected $casts = [
        'off_code' => 'string'
    ];


}
