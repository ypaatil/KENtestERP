<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleQcDeptStitchingModel extends Model
{
    use HasFactory;

    protected $table='sample_qc_stitching_detail'; 
	
	protected $fillable = [
        'sample_qc_dept_id','sample_indent_code','sample_qc_dept_date','color','size_array','size_qty_array','size_qty_total',
    ];
 
}
