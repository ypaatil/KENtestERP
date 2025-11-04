<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetailModel extends Model
{
    use HasFactory;

    protected $table='purchaseorder_detail';

	
	protected $fillable = [
        'pur_code','pur_date','bom_code','bom_type','class_id','Ac_code','sales_order_no', 'item_code',
        'item_qty','item_rate','disc_per','disc_amount','pur_cgst','camt','pur_sgst','samt','pur_igst',
        'iamt','amount','freight_hsn','freight_amt','total_amount','firm_id',
        'conQty' , 'unitIdM', 'priUnitd', 'SecConQty','secUnitId', 'poQty', 'poUnitId', 'rateM', 'totalQty',
        'created_at','updated_at',
    ];
}
