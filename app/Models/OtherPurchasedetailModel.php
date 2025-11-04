<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPurchasedetailModel extends Model
{
    use HasFactory;

            protected $table='purchase_detail';

	
	protected $fillable = [
        'pur_code','pur_date','Ac_code','item_code','item_qty','item_rate','disc_per','disc_amount','pur_cgst','camt','pur_sgst','samt','pur_igst','iamt','amount','total_amount','firm_id','created_at','updated_at',
    ];
    
}
