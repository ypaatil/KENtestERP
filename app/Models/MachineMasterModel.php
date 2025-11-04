<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineMasterModel extends Model
{
    use HasFactory;

    protected $table='machine_master';
    protected $primaryKey = 'MachineID';
	
	protected $fillable = [
        'machine_Id','MachineName','mc_make_Id','ModelNumber','machinetype_id','McDescription','MachineSrNo','pur_date','MachinePhoto','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}

