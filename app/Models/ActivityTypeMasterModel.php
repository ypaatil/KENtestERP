<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTypeMasterModel extends Model
{
    use HasFactory;

    protected $table='activity_type_master';
    protected $primaryKey = 'act_type_id';
    
    protected $fillable = [
        'act_type_name','userId','delflag','created_at','updated_at',
    ];
}
