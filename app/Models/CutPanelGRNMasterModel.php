<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutPanelGRNMasterModel extends Model
{
    use HasFactory;

    protected $table='cut_panel_grn_master';

    protected $primaryKey = 'cpg_code';
	
	protected $fillable = [
         'cpg_code', 'cpg_date','vpo_code', 'sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_qty','cons','fabric_used', 'vendor_rate',
         'vendor_amount', 'userId', 'narration', 'delflag', 'created_at', 'updated_at','c_code','cu_code'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'cpg_code' => 'string'
    ];



}
