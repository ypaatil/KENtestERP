<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SAHPPCMasterModel extends Model
{
    use HasFactory;

    protected $table='sah_ppc_master';
    protected $primaryKey = 'sah_ppc_master_id';
	
	protected $fillable = [
        'vendorId', 'line_id','sales_order_no','sam', 'noOfDays', 'totalAvaliableMin', 'month', 'monthValue', 'bookedMin', 'openMin','userId','updated_at'
    ];

}
