<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShadeModel extends Model
{
    use HasFactory;

    protected $table='shade_master';
    protected $primaryKey = 'shade_id';
	
	protected $fillable = [
        'shade_name' , 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
