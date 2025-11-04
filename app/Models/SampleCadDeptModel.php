<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleCadDeptModel extends Model
{
    use HasFactory;

    protected $table='sample_cad_dept_master';
    protected $primaryKey = 'sample_cad_dept_id';
	
	protected $fillable = [
        'sample_cad_dept_id','sample_indent_code','sample_cad_dept_date','Ac_code','brand_id','mainstyle_id','substyle_id','style_description','sam','sample_type_id','dept_type_id','sz_code','remark','delflag','userId','created_at','updated_at','delivery_date','material_avaliable_date'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
