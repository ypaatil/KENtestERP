<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTransactionMasterModel extends Model
{
    use HasFactory;

     protected $table='sale_transaction_master';
     protected $primaryKey='sr_no';
 
	protected $fillable = [
    'sale_code', 'sale_date','carton_packing_nos','Ac_code', 'tax_type_id', 'total_qty', 'Gross_amount' , 'freight_charges' ,  'Gst_amount', 'Net_amount',  'narration','firm_id','c_code' ,
    'terms_and_conditions', 'userId','delflag', 'created_at','updated_at','sales_head_id','isCancel','sent_through','address','delivary_note','delivary_note_date','mode_of_payment','transport_id',
    'bill_of_landing','vehicle_no','terms_of_delivery_id','bill_to','ship_to','destination','no_of_cartons','distance','transDocNo','transDocDate'
    ];
	
	
    
    protected $attributes = [
        'delflag' => 0,
     ];
	 
	   //protected $casts = [
    //     'sale_code' => 'string'
    // ];
}
