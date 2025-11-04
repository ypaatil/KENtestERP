<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutPanelGRNDetailModel extends Model
{
    use HasFactory;

    protected $table='cut_panel_grn_detail';
 
	protected $fillable = [
        'cpg_code', 'cpg_date','vpo_code','sales_order_no', 'Ac_code', 'vendorId',   'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'cpg_code' => 'string'
    ];


}
 