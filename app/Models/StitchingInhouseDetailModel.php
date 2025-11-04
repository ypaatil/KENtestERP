<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StitchingInhouseDetailModel extends Model
{
    use HasFactory;

    protected $table='stitching_inhouse_detail';
 
	protected $fillable = [
        'sti_code', 'sti_date','sales_order_no', 'Ac_code', 'vendorId','line_id',  'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'sti_code' => 'string'
    ];


}
