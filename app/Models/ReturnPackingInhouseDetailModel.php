<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPackingInhouseDetailModel extends Model
{
    use HasFactory;

    protected $table='return_packing_inhouse_detail';
 
	protected $fillable = [
        'rpki_code', 'rpki_date','sale_code','sales_order_no', 'Ac_code', 'vendorId', 'mainstyle_id' ,
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id','hsn_code', 'size_array', 'size_qty_array',
         'size_qty_total' ,'vendor_rate','is_opening','trans_sales_order_no', 'transfer_code','location_id',
         'rate','cgst','camt','sgst','samt','igst','iamt','amount','total_amount'
    ];

    protected $casts = [
        'rpki_code' => 'string'
    ];


}
