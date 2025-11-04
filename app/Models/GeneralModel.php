<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralModel extends Model
{
    use HasFactory;


    protected $table='sale_master';
        protected $primaryKey='sr_no';

	protected $fillable = [
    'bill_code','bill_date','pay_type','tax_type_id','Ac_code','Gross_amount','Gst_amount','Net_amount','narration','add1','add2','less1','less2','created_at','updated_at','firm_id','c_code','user_id',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];


}
