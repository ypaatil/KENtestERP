<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnableOutwardDetailModel extends Model
{
    use HasFactory;

            protected $table='returnableoutwarddetail';

	
	protected $fillable = [
        'RetOutwardcode','RetOutwardDate','Ac_code','item_code','hsn_code','unit_id','item_qty','item_rate','disc_per','disc_amount','pur_cgst','camt','pur_sgst','samt','pur_igst','iamt','amount','total_amount','return_date','firm_id','created_at','updated_at',
    ];

}
