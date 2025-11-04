<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricTransactionModel extends Model
{
    use HasFactory;

    protected $table='fabric_transaction';

    protected $primaryKey = 'tr_id';
	
	protected $fillable = [
        'tr_id', 'tr_code', 'tr_date', 'Ac_code', 'cp_id', 'po_code','item_code', 'part_id', 'track_code', 'old_meter', 'short_meter', 'rejected_meter', 'meter',
        'tr_type', 'rack_id','is_opening', 'userId', 'created_at', 'updated_at'   
    ];

    protected $attributes = [
        'usedflag' => 0,
     ];

     protected $casts = [
        'tr_code' => 'string',
        'track_code' => 'string'
    ];
}
