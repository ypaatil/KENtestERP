<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardRentedMachineModel extends Model
{
    use HasFactory;

    protected $table='inward_rented_machine_master';
    protected $primaryKey = 'purId';
	
	protected $fillable = [
        'pureDate','inwardtypeId','ac_code','totalAmount','rentedDate','machineimage','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}

