<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPurchaseModel extends Model
{
    use HasFactory;

        protected $table='purchase_master';
        protected $primaryKey='sr_no';

	protected $fillable = [
    'pur_code','pur_date','Ac_code','tax_type_id','pur_bill_no','pur_bill_date','Gross_amount','Gst_amount','Net_amount','narration','firm_id','c_code','Purchase_Ac_code','tds_per','tds_amt','payable_amt','userId','created_at','updated_at',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];
}
