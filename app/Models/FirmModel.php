<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirmModel extends Model
{
    use HasFactory;

    protected $table='firm_master';
    protected $primaryKey = 'firm_id';
	
	protected $fillable = [
        'firm_name', 'Address', 'c_id', 'state_id', 'dist_id', 'taluka_id','city_name', 'gst_no', 'pan_no', 'owner_name', 'mobile_no', 'email_id','bank_name', 'account_name', 'account_no', 'ifsc_code', 'ac_id', 'reg_id', 'userId', 'delflag', 'created_at', 'updated_at' 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
