<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourlyProductionMasterModel extends Model
{
    use HasFactory;
    
    protected $table='hourly_production_entry_masters';
    protected $primaryKey = 'hourlyProductionId';           
    
    
    
    protected $fillable = [
        'hourlyProductionId','hourlyEntryDate','sub_company_id','dept_id','mainstyle_id','total_production','userId','is_deleted','is_deleted','created_at','updated_at'];
 
 
  protected $attributes = [
        'is_deleted' => 0,
     ];
     
 
}

