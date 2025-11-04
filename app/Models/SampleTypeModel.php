<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleTypeModel extends Model
{
    use HasFactory;

    protected $table='sample_type_master';
    protected $primaryKey = 'sample_type_id';
	
	protected $fillable = [
        'dept_type_id','sample_type_name','delflag','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
