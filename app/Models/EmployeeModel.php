<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    use HasFactory;

    protected $table='employeemaster_operation';
    protected $primaryKey = 'employeeId';
	
	protected $fillable = [
        'employeeCode','fullName', 'maincompany_id', 'sub_company_id','egroup_id','employee_status_id','emp_cat_id', 'delflag', 'created_at', 'updated_at','userId'

    ];

    protected $attributes = [
        'delflag' => 0,
     ];


}
