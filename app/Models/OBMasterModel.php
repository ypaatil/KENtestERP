<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OBMasterModel extends Model
{
    use HasFactory;

    protected $table='ob_masters';
    protected $primaryKey = 'ob_id';
    
    
    protected $fillable = [
        'sub_company_id','mainstyle_id','total_sam','total_rate','total_rate3','total_rate4','total_rate5','total_rate6','created_at','updated_at'];
 

  protected $attributes = [
        'is_deleted' => 0,
     ];
 
 
}

