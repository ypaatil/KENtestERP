<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimsOutwardCuttingDepartmentMasterModel extends Model
{
    use HasFactory;

    protected $table='trims_outward_cutting_department_master';

    protected $primaryKey = 'tocd_code'; 
	
	protected $fillable = [
        'tocd_code', 'tocd_date', 'dc_no', 'outward_date', 'vendorId', 'cutting_po_no', 'mainstyle_id', 'substyle_id',   
        'fg_id', 'style_no', 'style_description','total_outward_meter','total_received_meter','remark', 'userId', 'delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
    ];

    protected $casts = [
        'tocd_code' => 'string'
    ];
   
}
