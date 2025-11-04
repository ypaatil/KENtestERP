<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionEntryOperationModel extends Model
{
    use HasFactory;
    
    protected $table='daily_production_entry_masters';
    protected $primaryKey = 'daily_pr_entry_id';           
    
    
    protected $fillable = [
        'daily_pr_entry_id','daily_pr_date','sub_company_id','dept_id','mainstyle_id','group_id','total_efficiency','is_style_change','sam_1','output_1','sam_2','output_2','overall_sam','overall_output','total_present','overall_efficiency','is_deleted','created_at','updated_at'];
 
 
  protected $attributes = [
        'is_deleted' => 0,
     ];
     
 
}

