<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiPaymentMasterModel extends Model
{
    use HasFactory;


        protected $table='multipayment_master';
        protected $primaryKey='sr_no';


	protected $fillable = [
    'tr_code','tr_date','firm_id','pay_mode','cr_code','total_amount','narration','CounterId','userId','created_at','updated_at','c_code',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];
}
