<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleCadDeptDetailModel extends Model
{
    use HasFactory;

    protected $table='sample_cad_dept_detail'; 
	
	protected $fillable = [
        'sample_cad_dept_id','sample_indent_code','sample_indent_date','delivery_date','bom_type_id','material_received_status_id',
    ];
 
}
