<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taluka extends Model
{
    use HasFactory;

      protected $table='taluka_master';
      protected $primaryKey = 'tal_id';
	
	protected $fillable = [
        'country_id','state_id','dist_id','taluka','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
