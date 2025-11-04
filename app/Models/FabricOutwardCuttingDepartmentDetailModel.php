<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricOutwardCuttingDepartmentDetailModel extends Model
{
    use HasFactory;

    protected $table='fabric_outward_cutting_department_details'; 
	
	protected $fillable = [
        'focd_code', 'focd_date', 'roll_no', 'suplier_roll_no', 'item_code', 'item_name', 'color_name', 'quality_code', 'shade_id', 'width',   
        'challan_meter', 'receive_meter', 'outward_meter'
    ];
}
