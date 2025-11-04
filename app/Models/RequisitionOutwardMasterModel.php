<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionOutwardMasterModel extends Model
{
    use HasFactory;

     protected $table='requisition_outward_master';
     protected $primaryKey='requisition_outward_no';

	protected $fillable = [
    'requisition_outward_no','requisition_outward_date','requisitionNo','dept_id','machineId','issueTo','reasonId','firm_id','userId','created_at','updated_at',
    ];
    
    protected $attributes = [
        'isDeleted' => 0,
     ];
 
   protected $casts = [
        'requisition_outward_no' => 'string'
    ];
    
}
