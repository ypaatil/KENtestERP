<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWiseOperationModel extends Model
{
    use HasFactory; 

    protected $table='employee_wise_operations';  
	
	protected $fillable = [
       'employeeCode','operationNameId'
    ];

}
 