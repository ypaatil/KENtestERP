<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutPanelIssueDetailModel extends Model
{
    use HasFactory;

    protected $table='cut_panel_issue_detail';
 
	protected $fillable = [
        'cpi_code', 'cpi_date','vpo_code', 'sales_order_no', 'Ac_code', 'vendorId', 'line_id', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id', 'size_array', 'size_qty_array','size_qty_total' ,'vendor_rate'
    ];

    protected $casts = [
        'cpi_code' => 'string'
    ];


}
 