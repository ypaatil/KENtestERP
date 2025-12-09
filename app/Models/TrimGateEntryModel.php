<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimGateEntryModel extends Model
{
    use HasFactory;

    protected $table='trim_gate_entry_master';

    protected $primaryKey = 'tge_code';
	
	protected $fillable = [
        'tge_code', 'tge_date','po_code', 'po_code2', 'dc_no', 'dc_date', 'invoice_no', 'invoice_date', 'Ac_code', 'location_id',   
        'lr_no', 'transport_name','vehicle_no', 'total_qty','total_received_meter','total_amt','remark', 'userId', 'delflag', 'created_at', 'updated_at','bill_to','buyer_id'
    ];

    protected $attributes = [
        'delflag' => 0,
    ];
     
    protected $casts = [
        'tge_code' => 'string'
    ];
}