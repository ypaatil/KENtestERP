<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineModelMasterModel extends Model
{
    use HasFactory;

    protected $table='machine_model_master';
    protected $primaryKey = 'mc_model_id';
	
	protected $fillable = [
        'mc_model_name','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
