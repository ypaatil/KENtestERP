<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    use HasFactory;

      protected $table='city_master';
      protected $primaryKey = 'city_id';
	
	protected $fillable = [
        'country_id','state_id','dist_id','taluka_id','city_name','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
