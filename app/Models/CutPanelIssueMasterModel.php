<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutPanelIssueMasterModel extends Model
{
    use HasFactory;

    protected $table='cut_panel_issue_master';

    protected $primaryKey = 'cpi_code';
	
	protected $fillable = [
         'cpi_code', 'cpi_date', 'sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty', 'vendor_rate',
         'vendor_amount', 'line_id', 'userId', 'narration', 'delflag', 'created_at', 'updated_at','c_code', 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'cpi_code' => 'string'
    ];



}
