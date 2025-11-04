<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePurchaseOrderModel extends Model
{
    use HasFactory;

     protected $table='spare_purchase_order';
     protected $primaryKey='sr_no';
 
	protected $fillable = [
    'pur_code','cat_id','pur_date','Ac_code','po_type_id','tax_type_id', 'total_qty', 'Gross_amount','Gst_amount','totFreightAmt','Net_amount',
    'narration','firm_id','c_code','gstNo','address','deliveryAddress','supplierRef','terms_and_conditions',
    'delivery_date','userId','created_at','updated_at',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];
}
