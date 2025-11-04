<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderModel extends Model
{
    use HasFactory;

     protected $table='purchase_order';
     protected $primaryKey='sr_no';
 
	protected $fillable = [
    'pur_code','bom_code','bom_type','pur_date','Ac_code','po_type_id','tax_type_id', 'total_qty', 'Gross_amount','Gst_amount','totFreightAmt','Net_amount',
    'narration','firm_id','c_code','gstNo','address','deliveryAddress','supplierRef','terms_and_conditions',
    'delivery_date','po_status','closeDate','userId','approveFlag','buyer_id','created_at','updated_at','bill_to','ship_to'
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];
}
