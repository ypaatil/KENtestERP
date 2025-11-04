<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T_And_A_DetailModel extends Model
{
    use HasFactory;
    protected $table='t_and_a_detail';

    protected $primaryKey ='t_and_a_id';

    protected $fillable = [
        'sr_no','t_and_a_id','tr_code','act_id', 'target_date','actual_date'];

     protected $casts = [
        't_and_a_id' => 'string'
    ];
}
