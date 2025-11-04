<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T_And_A_TemplateDetailModel extends Model
{
    use HasFactory;
    protected $table='t_and_a_templatedetail';

    protected $primaryKey ='t_and_a_tid';

    protected $fillable = [
        'sr_no','t_and_a_tid', 'act_id','days','dact_id'];

     protected $casts = [
        't_and_a_tid' => 'string'
    ];
}
