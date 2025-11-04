<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MIStoreDetailModel extends Model
{
    use HasFactory;

            protected $table='store_inward_detail';

	
	protected $fillable = [
        'storeInCode','storeInward_date','Ac_code','item_code','hsn_code','item_qty','item_rate','disc_per','disc_amount','pur_cgst','camt','pur_sgst','samt','pur_igst','iamt','amount','freight_hsn','freight_amt','total_amount','firm_id','created_at','updated_at',
    ];


}
