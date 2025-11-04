<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricGateEntryModel extends Model
{
    use HasFactory;

    protected $table='fabric_gate_entry_master';

    protected $primaryKey = 'fge_code';
	
	protected $fillable = [
        'fge_code', 'fge_date','po_code', 'po_code2', 'dc_no', 'dc_date', 'invoice_no', 'invoice_date', 'Ac_code', 'location_id',   
        'lr_no', 'transport_name','vehicle_no', 'is_manual', 'total_roll','total_meter','total_received_meter','total_amount','remark', 'userId', 'delflag', 'created_at', 'updated_at','bill_to'
    ];

    protected $attributes = [
        'delflag' => 0,
    ];
     
    protected $casts = [
        'fge_code' => 'string'
    ];
}
