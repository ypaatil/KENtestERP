<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityMasterModel extends Model
{
    use HasFactory;

    protected $table='activity_master';
    protected $primaryKey = 'act_id';
    
    protected $fillable = [
        'act_name','act_type_id','dept_id','userId','delflag','created_at','updated_at',
    ];
}
