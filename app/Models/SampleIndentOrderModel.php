<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleIndentOrderModel extends Model
{
    use HasFactory;

    protected $table='sample_indent_order'; 
	
	protected $fillable = [
        'sample_indent_id','sample_indent_code','sample_indent_date','color','size_array','size_qty_array','order_qty',
    ];
 
}
