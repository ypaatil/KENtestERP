<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricOutwardModel extends Model
{
    use HasFactory;
    protected $table='fabric_outward_master';

    protected $primaryKey = 'fout_code';
	
	protected $fillable = [
        'fout_code', 'fout_date','out_type_id','vendorId', 'vpo_code', 'sample_indent_code', 'mainstyle_id', 'substyle_id', 'fg_id', 'style_no', 'style_description', 'total_meter', 'total_taga_qty', 
        'narration', 'c_code', 'userId','CounterId','delflag', 'created_at', 'updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'fout_code' => 'string',
        
    ];
}
