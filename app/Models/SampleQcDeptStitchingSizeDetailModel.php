<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleQcDeptStitchingSizeDetailModel extends Model
{
    use HasFactory;

    protected $table='sample_qc_dept_size_detail'; 
	
	protected $fillable = [
        'sample_qc_dept_id','sample_indent_code','sample_qc_dept_date','color','size_array','s1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','s12','s13','s14','s15','s16','s17','s18','s19','s20','size_qty_total'
    ];
 
}
