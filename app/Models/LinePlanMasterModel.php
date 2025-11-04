<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinePlanMasterModel extends Model
{
    use HasFactory;

    protected $table='line_plan_masters';
    protected $primaryKey = 'line_plan_id';
    
    protected $fillable = [
        'line_date','sub_company_id','mainstyle_id','dept_id','station_no','target_efficiency','created_at','updated_at'];
 
  protected $attributes = [
        'is_deleted' => 0,
     ];
     
          protected static function boot(){
      parent::boot();
 
    static::created(function ($model) {
      $model->station_no = $model->line_plan_id;
      $model->save();
    });
  }
 
 
}

