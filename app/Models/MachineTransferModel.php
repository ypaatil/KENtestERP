<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineTransferModel extends Model
{
    use HasFactory;

    protected $table='machine_transfer_master';
    protected $primaryKey = 'transId';
	
	protected $fillable = [
        'transDate','fromLocName','toLocName','vehicleNumber','driveName','remark','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}