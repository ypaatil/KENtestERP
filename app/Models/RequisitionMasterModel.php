<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionMasterModel extends Model
{
    use HasFactory;

     protected $table='requisition_master';
     protected $primaryKey='requisitionNo';

	protected $fillable = [
    'requisitionNo','requisitionDate','requisitionTypeId','dept_id','machineId','issueTo','reasonId','firm_id','userId','requisitionApproveFlag','created_at','updated_at',
    ];
    
    protected $attributes = [
        'isDeleted' => 0,
     ];
 
   protected $casts = [
        'requisitionNo' => 'string'
    ];


}
