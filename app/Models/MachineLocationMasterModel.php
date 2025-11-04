<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineLocationMasterModel extends Model
{
    use HasFactory;

    protected $table='machine_location_master';
    protected $primaryKey = 'mc_loc_Id';
	
	protected $fillable = [
        'machine_location_name','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}

