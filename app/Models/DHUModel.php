<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DHUModel extends Model
{
    use HasFactory;

    protected $table='dhu_master';

    protected $primaryKey = 'dhu_code';
	
	protected $fillable = [
         'dhu_code','dhu_date','Ac_code','substyle_id','fg_id','style_no','style_description','vendorId','sales_order_no','vw_code','line_no','mainstyle_id','total_defect_qty','userId','created_at','updated_at','c_code' 
    ];

     protected $casts = [
        'dhu_code' => 'string',
        
    ]; 
}
