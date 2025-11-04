<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T_And_A_MasterModel extends Model
{
    use HasFactory;

    protected $table='t_and_a_master';

    protected $primaryKey ='t_and_a_id';

    protected $fillable = [
        't_and_a_id', 'tr_code','dterm_id','Ac_code','order_received_date','mainstyle_id','substyle_id','fg_id','style_no','style_description','shipment_date','userId','delflag','created_at','updated_at'];

     protected $casts = [
        't_and_a_id' => 'string'
    ];
}
