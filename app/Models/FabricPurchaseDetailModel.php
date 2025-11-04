<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricPurchaseDetailModel extends Model
{
    use HasFactory;

        protected $table='purchase_fabric_details';
	
	protected $fillable = [
        'fpur_code','fpur_date','cp_id','fpur_bill','Ac_code','item_code','fpur_style_no','fpur_mtr','fpur_qty','pur_rate','Amount','created_at','updated_at',
    ];

     protected $attributes = [
        'usedFlag' => 0,
     ];
}
