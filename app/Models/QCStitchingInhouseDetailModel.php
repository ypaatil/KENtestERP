<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QCStitchingInhouseDetailModel extends Model
{
    use HasFactory;

    protected $table='qcstitching_inhouse_detail';
 
	protected $fillable = [
       'qcsti_code', 'qcsti_date', 'vw_code', 'Ac_code', 'sales_order_no', 'vendorId','line_id',
       'mainstyle_id', 'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id',
       'item_code', 'size_array', 'size_qty_array', 'size_qty_total', 'vendor_rate'
    ];

    protected $casts = [
        'qcsti_code' => 'string'
    ];


}
