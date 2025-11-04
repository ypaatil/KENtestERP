<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSaleDetailModel extends Model
{
    use HasFactory;

            protected $table='sale_detail';

	
	protected $fillable = [
        'bill_code','bill_date','Ac_code','item_code','item_qty','item_rate','disc_per','disc_amount','mrp','pur_cgst','camt','pur_sgst','samt','pur_igst','iamt','amount','total_amount','firm_id','created_at','updated_at',
    ];
}