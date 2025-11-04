<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimsOutwardMasterModel extends Model
{
    use HasFactory;
    
       protected $table='trimOutwardMaster';
        protected $primaryKey = 'trimOutCode';
	
	protected $fillable = [
       'trimOutCode','tout_date', 'out_type_id',   'vendorId','trim_type','vpo_code','vw_code','sample_indent_code','mainstyle_id','substyle_id','fg_id','style_no','style_description',
       'total_qty','c_code','userId','created_at','updated_at','ship_to'
    ];

    protected $attributes = [
        'delflag' => 0,
         
     ];

     protected $casts = [
        'trimOutCode' => 'string',
        
    ]; 
    
    
}
