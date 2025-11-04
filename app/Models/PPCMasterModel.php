<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPCMasterModel extends Model
{
    use HasFactory;

    protected $table='ppc_master';
    protected $primaryKey = 'sr_no';
	
	protected $fillable = [
        'vendorId', 'line_id','sales_order_no','color_id','color_order_qty','machine_count', 'available_mins', 'line_efficiency', 'sam', 'production_capacity', 'target', 'start_date', 'end_date', 'userId', 'endFlag'
    ];

    protected $attributes = [
        'endFlag' => 0,
     ];
}
