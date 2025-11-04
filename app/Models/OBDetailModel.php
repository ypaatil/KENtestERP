<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OBDetailModel extends Model
{
    use HasFactory;

    protected $table='ob_details';
    protected $primaryKey = 'sr_no';
    
    

    protected $fillable = [
        'ob_id','sub_company_id','mainstyle_id','operation_id','operation_name','group_id','machine_type_id','sam','rate','rate3','rate4','rate5','rate6','required_skill_set','created_at','updated_at'];
 

 
}

