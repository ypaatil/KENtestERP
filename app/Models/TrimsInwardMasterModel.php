<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimsInwardMasterModel extends Model
{
    use HasFactory;
    
     protected $table='trimsInwardMaster';
     protected $primaryKey='sr_no';
     
 
	protected $fillable = ['trimCode','po_code', 'trimDate', 'is_opening','invoice_no','invoice_date','Ac_code','po_type_id','totalqty','bill_to',
                    'total_amount','isReturnFabricInward','vw_code','is_opening','location_id','delflag','userId','created_at','updated_at','tge_code'];
    
    protected $attributes = [
        'delflag' => 0,
     ];
     
    
}
