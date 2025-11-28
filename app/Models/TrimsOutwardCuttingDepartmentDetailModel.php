<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimsOutwardCuttingDepartmentDetailModel extends Model
{
    use HasFactory;

    protected $table='trims_outward_cutting_department_details';  
	
	protected $fillable = [
        'tocd_code', 'tocd_date', 'po_code', 'item_code', 'item_name','item_qty', 'outward_qty'
    ];
}
