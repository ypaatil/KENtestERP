<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineMakeMasterModel extends Model
{
    use HasFactory;

    protected $table='machine_make_master';
    protected $primaryKey = 'mc_make_Id';
	
	protected $fillable = [
        'machine_make_name','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
