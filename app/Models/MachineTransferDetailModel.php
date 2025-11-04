<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineTransferDetailModel extends Model
{
    use HasFactory;

    protected $table='machine_transfer_detail';
    protected $primaryKey = 'transId';
	
	protected $fillable = [
        'transId','transDate','fromLocName','toLocName','vehicleNumber','driveName','remark','purId','MachineID','mc_make_Id','modelNumber','machinetype_id','Qty',
    ];

}