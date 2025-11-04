<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPackingInhouseSizeDetailModel extends Model
{
    use HasFactory;

    protected $table='return_packing_inhouse_size_detail';
 
	protected $fillable = [
         'rpki_code', 'rpki_date', 'sale_code', 'Ac_code', 'sales_order_no', 'vendorId', 'mainstyle_id',
         'substyle_id', 'fg_id', 'style_no', 'style_description', 'color_id','hsn_code', 'item_code',
         'size_array', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's11', 
         's12', 's13', 's14', 's15', 's16', 's17', 's18', 's19', 's20', 'size_qty_total' ,'is_opening' ,'is_transfered','trans_sales_order_no', 'transfer_code','location_id',
         'rate','cgst','camt','sgst','samt','igst','iamt','amount','total_amount'
    ];

    protected $casts = [
        'rpki_code' => 'string'
    ];


}
