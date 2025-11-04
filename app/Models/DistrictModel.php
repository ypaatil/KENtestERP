<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    use HasFactory;

     protected $table='district_master';
    protected $primaryKey = 'd_id';
	
	protected $fillable = [
        'c_id','state_id','d_name','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
