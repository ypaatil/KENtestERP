<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    use HasFactory;

    protected $table='department_master';
    protected $primaryKey = 'dept_id';
	
	protected $fillable = [
        'dept_name','details', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
