<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionEntryDetailOperationModel extends Model
{
    use HasFactory;

    protected $table='daily_production_entry_details_operation';
    protected $primaryKey = 'sr_no';
    
    
    protected $fillable = [
        'daily_pr_entry_id','daily_pr_date','sub_company_id','mainstyle_id','dept_id','operation_id','operation_name','employeeCode','sam','pieces','efficiency','station_no','remark','is_half_day','created_at','updated_at'];
 


 
 
}

