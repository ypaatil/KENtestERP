<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiPaymentDetailModel extends Model
{
    use HasFactory;


            protected $table='multipayment_detail';          
	
	protected $fillable = [
        'tr_code','tr_date','firm_id','pay_mode','cr_code','Ac_code','tr_nos','bill_amount','paying_amount','CounterId','UserId','created_at','updated_at',
    ];
    
}
