<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleIndentPackingTrimsModel extends Model
{
    use HasFactory;

    protected $table='sample_indent_packing_trims'; 
	
	protected $fillable = [
        'sample_indent_id','sample_indent_code','sample_indent_date','packing_trims_item_code','packing_trims_qty'
    ];
 
}
