<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimGateEntryDetailModel extends Model
{
    use HasFactory;

    protected $table='trim_gate_entry_details'; 
	
	protected $fillable = [
        'tge_code', 'tge_date', 'po_code','po_code2','is_manual','item_name', 'item_code', 'item_description', 'unit_id','challan_qty',  'receive_qty',   
        'rate', 'amount', 'remarks'
    ];
}