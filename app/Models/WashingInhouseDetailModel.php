<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WashingInhouseDetailModel extends Model
{
    use HasFactory;

    protected $table='washing_inhouse_detail';
 
	protected $fillable = [
        'wash_code', 'wash_date','vpo_code','sales_order_no', 'Ac_code','process_id', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'wash_code' => 'string'
    ];


}
