<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardRentedMachineDetailModel extends Model
{
    use HasFactory;

    protected $table='inward_rented_machine_detail';
    protected $primaryKey = 'purId';
	
	protected $fillable = [
        'purId','pureDate','inwardtypeId','ac_code','MachineID','rentedDate','machineCode','mc_make_Id','machinetype_id','purchaseRate','Qty','amount','totalAmount','mc_loc_Id',
    ];

}

