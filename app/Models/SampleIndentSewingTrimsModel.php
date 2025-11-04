<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleIndentSewingTrimsModel extends Model
{
    use HasFactory;

    protected $table='sample_indent_sewing_trims'; 
	
	protected $fillable = [
        'sample_indent_id','sample_indent_code','sample_indent_date','sewing_trims_item_code','sewing_trims_qty'
    ];
 
}
