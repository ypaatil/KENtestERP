<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T_And_A_TemplateMasterModel extends Model
{
    use HasFactory;

    protected $table='t_and_a_templatemaster';

    protected $primaryKey ='t_and_a_tid';

    protected $fillable = [
        't_and_a_tid','dterm_id','userId','delflag','created_at','updated_at'];

     protected $casts = [
        't_and_a_tid' => 'string'
    ];
}
