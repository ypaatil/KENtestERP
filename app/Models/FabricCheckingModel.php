<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricCheckingModel extends Model
{
    use HasFactory;

    protected $table='fabric_checking_master';

    protected $primaryKey = 'chk_code';
    
    
	
	protected $fillable = [
        
        'chk_code', 'chk_date','in_code', 'cp_id', 'Ac_code','invoice_no','po_code','invoice_date','po_type_id',  'total_meter', 
        'total_taga_qty','total_kg','in_narration', 'is_opening','c_code','userId','CounterId','delflag', 'created_at', 'updated_at','bill_to'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'chk_code' => 'string',
        
    ];


}
