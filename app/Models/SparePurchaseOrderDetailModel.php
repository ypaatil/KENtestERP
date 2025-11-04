<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePurchaseOrderDetailModel extends Model
{
    use HasFactory;

    protected $table='spare_purchase_order_detail';

	
	protected $fillable = [
        'pur_code','pur_date','cat_id','class_id','Ac_code', 'spare_item_code',
        'item_qty','item_rate','disc_per','disc_amount','pur_cgst','camt','pur_sgst','samt','pur_igst',
        'iamt','amount','freight_hsn','freight_amt','total_amount','firm_id', 'totalQty'
    ];
}
