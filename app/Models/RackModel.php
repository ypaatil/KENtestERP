<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackModel extends Model
{
    use HasFactory;

    protected $table='rack_master';
    protected $primaryKey = 'rack_id';
	
	protected $fillable = [
          'rack_name',  'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
