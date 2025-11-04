<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineMainTypeMasterModel extends Model
{
    use HasFactory;

    protected $table='machine_main_type_master';
    protected $primaryKey = 'machine_Id';
	
	protected $fillable = [
        'machine_name','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}


