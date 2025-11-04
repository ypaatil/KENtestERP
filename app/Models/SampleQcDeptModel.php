<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleQcDeptModel extends Model
{
    use HasFactory;

    protected $table='sample_qc_dept_master';
    protected $primaryKey = 'sample_qc_dept_id';
	
	protected $fillable = [
        'sample_qc_dept_id','sample_indent_code','sample_qc_dept_date','Ac_code','brand_id','mainstyle_id','substyle_id','style_description','sam','sample_type_id','dept_type_id','sz_code','delflag','userId','created_at','updated_at','actual_etd'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
