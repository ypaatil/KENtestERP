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
        'total_qty', 'exchange_rate','inr_rate','order_rate', 'order_value', 'shipped_qty', 'balance_qty',  'sz_ws_total',   'job_status_id', 'og_id', 'brand_id', 'order_received_date','order_type',
         'season_id', 'currency_id', 'ptm_id', 'dterm_id', 'style_description', 'style_img_path',
        'ship_id', 'country_id', 'warehouse_id', 'shipment_date', 'plan_cut_date', 'inspection_date', 'ex_factory_date',
        'buyer_document_path','tech_pack','measurement_sheet','fit_pp_comments','approved_fabric_trim','narration','unit_id','merchant_id','PDMerchant_id', 'from_tna_date', 'to_tna_date', 'order_close_date','sam','userId', 'delflag', 'c_code', 'created_at', 'updated_at',
        'orderCategoryId','in_out_id'
             
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'tr_code' => 'string'
    ];


}
