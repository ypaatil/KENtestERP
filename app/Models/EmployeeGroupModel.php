<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeGroupModel extends Model
{
    use HasFactory;

    protected $table='emp_groupmaster';
    protected $primaryKey = 'egroup_id';
	
	protected $fillable = [
        'egroup_name', 'userId', 'created_at', 'updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
