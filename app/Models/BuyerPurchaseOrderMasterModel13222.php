<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPurchaseOrderMasterModel extends Model
{
    use HasFactory;

    protected $table='buyer_purchse_order_master';

    protected $primaryKey = 'tr_code';
	
	protected $fillable = [
        'tr_code', 'tr_date', 'Ac_code', 'mainstyle_id', 'substyle_id', 'fg_id', 'style_no', 'po_code', 'sz_code',
        'total_qty', 'order_rate', 'order_value', 'shipped_qty', 'balance_qty',     'job_status_id', 'og_id', 'brand_id', 'order_received_date',
         'season_id', 'currency_id', 'ptm_id', 'dterm_id', 'style_description', 'style_img_path',
        'ship_id', 'country_id', 'warehouse_id', 'shipment_date', 'plan_cut_date', 'inspection_date', 'ex_factory_date',
        'buyer_document_path', 'narration','unit_id', 'userId', 'delflag', 'c_code', 'created_at', 'updated_at'  
             
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'tr_code' => 'string'
    ];


}
