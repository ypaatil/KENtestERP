<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table='state_master';
    protected $primaryKey = 'state_id';
	
	protected $fillable = [
        'country_id','state_name','delflag','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
