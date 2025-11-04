<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleIndentFabricModel extends Model
{
    use HasFactory;

    protected $table='sample_indent_fabric'; 
	
	protected $fillable = [
        'sample_indent_id','sample_indent_code','sample_indent_date','fabric_item_code','fabric_qty'
    ];
 
}
