<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingInhouseDetailModel extends Model
{
    use HasFactory;

    protected $table='finishing_inhouse_detail';
 
	protected $fillable = [
        'fns_code', 'fns_date','vpo_code','sales_order_no', 'Ac_code','process_id', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'fns_code' => 'string'
    ];


}
