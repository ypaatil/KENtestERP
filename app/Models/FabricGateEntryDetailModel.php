<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricGateEntryDetailModel extends Model
{
    use HasFactory;

    protected $table='fabric_gate_entry_details'; 
	
	protected $fillable = [
        'fge_code', 'fge_date', 'po_code','po_code2','is_manual','item_name', 'item_code', 'item_description', 'challan_qty', 'no_of_roll', 'receive_qty',   
        'rate', 'amount', 'remarks'
    ];
}
