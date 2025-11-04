<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialInwardStoreModel extends Model
{
    use HasFactory;

     protected $table='store_inward_master';
     protected $primaryKey='sr_no';

	protected $fillable = [
    'storeInCode','storeInward_date','inwardTypeId','Ac_code','tax_type_id','pur_bill_no','pur_bill_date','dc_no','dc_date','po_no','Gross_amount','Gst_amount','totFreightAmt','Net_amount','narration','firm_id','c_code','loc_id','gstNo','address','userId','inwardApproveFlag','created_at','updated_at',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];
}
