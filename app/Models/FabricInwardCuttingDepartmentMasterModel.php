<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricInwardCuttingDepartmentMasterModel extends Model
{
    use HasFactory;

    protected $table='fabric_inward_cutting_department_master';

    protected $primaryKey = 'ficd_code';
	
	protected $fillable = [
        'ficd_code', 'ficd_date', 'dc_no', 'outward_date', 'vendorId', 'cutting_po_no', 'mainstyle_id', 'substyle_id',   
        'fg_id', 'style_no', 'style_description', 'total_challan_meter','total_received_meter','total_roll','remark', 'userId', 'delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
    ];

    protected $casts = [
        'ficd_code' => 'string'
    ];
   
}
