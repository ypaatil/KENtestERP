<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerModel extends Model
{
    use HasFactory;

    protected $table='ledger_master';
    protected $primaryKey = 'ac_code';
	
	protected $fillable = [
        'ac_name','ac_short_name','trade_name', 'group_code', 'group_main', 'op_bal', 'op_dc', 'address', 'c_id', 'state_id', 'dist_id', 'taluka_id', 'city_name', 'phone', 'mobile','status_id', 'email', 'pan_no', 'gst_no', 
        'pin_code','msme_code','cin_no','branch_name','note','adhar_no', 'bt_id', 'bt_id1', 'bt_id2', 'bank_name', 'account_name', 'ac_id', 'account_no', 'ifsc_code', 'tds_type', 'tds_per', 'userId','created_at', 'updated_at','isPackingInward'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];


}
