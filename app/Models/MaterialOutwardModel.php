<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOutwardModel extends Model
{
    use HasFactory;


    protected $table='materialoutwardmaster';

    protected $primaryKey ='materialOutwardCode';


	protected $fillable = [
        'materialOutwardCode', 'materialOutwardDate','loc_id','totalqty','remark','delflag','userId','created_at','updated_at',
    ];


     protected $casts = [
        'materialOutwardCode' => 'string'
    ];



}
