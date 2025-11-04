<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleIndentModel extends Model
{
    use HasFactory;

    protected $table='sample_indent_master';
    protected $primaryKey = 'sample_indent_id';
	
	protected $fillable = [
        'sample_indent_id','sample_indent_code','sample_indent_date','Ac_code','brand_id','mainstyle_id','substyle_id','style_description','sam','sample_type_id','dept_type_id','sz_code','sample_required_date','remark','delflag','userId','created_at','updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
