<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialReturnModel extends Model
{
    use HasFactory;


    protected $table='materialReturnMaster';

    protected $primaryKey ='materialReturnCode';


	protected $fillable = [
        'materialReturnCode', 'materialReturnDate','loc_id','totalqty','remark','delflag','userId','created_at','updated_at',
    ];


     protected $casts = [
        'materialReturnCode' => 'string'
    ];



}
