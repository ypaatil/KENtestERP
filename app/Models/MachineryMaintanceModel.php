<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineryMaintanceModel extends Model
{
    use HasFactory;

    protected $table='machinery_maintance';
    protected $primaryKey = 'mcmaintainceId';
	
	protected $fillable = [
        'date','mc_loc_Id','purId','MachineID','purpose','proAddress','rentedId','totalDownTime','remark','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}