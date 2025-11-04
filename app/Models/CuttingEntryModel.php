<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingEntryModel extends Model
{
    use HasFactory; 

    protected $table='cutting_entry_master';
    protected $primaryKey = 'cuttingEntryId';
	
	protected $fillable = [
       'cuttingEntryDate','main_style_id', 'sales_order_no','fg_id','delflag','vendorId','jpart_id','created_at', 'updated_at','userId','total_cut_qty'
    ];

    protected $attributes = [
        'delflag' => 0,
         
    ];


}
 