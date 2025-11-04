<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinePlanDetailModel extends Model
{
    use HasFactory;
    
    protected $table='line_plan_detail';

    protected $primaryKey ='sr_no';
    
    
 protected $fillable = ['line_plan_id','sub_company_id','operation_id','operation_name','group_id','machine_type_id','dept_id','sam','required_skill_set',"employeeCode",'created_at','updated_at']; 
      
  
}
