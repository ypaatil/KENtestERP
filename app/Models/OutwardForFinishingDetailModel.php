<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardForFinishingDetailModel extends Model
{
    use HasFactory;

    protected $table='outward_for_finishing_detail';
 
	protected $fillable = [
        'off_code', 'off_date','vpo_code','process_id', 'sales_order_no', 'Ac_code', 'vendorId',  'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'off_code' => 'string'
    ];


}
 