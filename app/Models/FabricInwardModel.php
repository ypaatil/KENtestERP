<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricInwardModel extends Model
{
    use HasFactory;


    protected $table='inward_master';

    protected $primaryKey = 'sr_no';
	
	protected $fillable = [
      
        
        'in_code', 'in_date', 'invoice_no', 'invoice_date', 'cp_id', 'Ac_code', 'po_code', 'po_type_id',   
        'total_meter', 'total_kg', 'total_taga_qty','total_amount', 'in_narration', 'is_opening', 'fge_code','location_id',
        'isReturnFabricInward','isOutsideVendor','vpo_code','vendorId','c_code', 'userId', 'delflag', 'CounterId', 'created_at', 'updated_at'
        
        
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     



   
}
