<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineTypeModel extends Model
{
    use HasFactory;

    protected $table='machine_type_master';
    protected $primaryKey = 'machinetype_id';
	
	protected $fillable = [
        'machinetype_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
