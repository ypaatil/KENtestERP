<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricInwardReportModel extends Model
{
    use HasFactory;
    
        protected $table='inward_master';

    protected $primaryKey = 'in_code';
	
	protected $fillable = [
        'in_code', 'in_date', 'cp_id', 'Ac_code', 'po_code', 'gp_no','fg_id', 'style_no', 'total_meter','total_kg', 'total_taga_qty', 
        'in_narration', 'c_code', 'userId','CounterId','delflag', 'created_at', 'updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'in_code' => 'string',
        
    ];

}
