<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingBalanceDetailModel extends Model
{
    use HasFactory;
    protected $table='cutting_balance_details';
 
	protected $fillable = [
        'cu_code', 'cu_date', 'lot_no', 'job_code', 'style_no', 'Ac_code', 'table_id', 'table_avg', 
        'track_code','part_id', 'color_id', 'width', 'meter','shade_id',  'layers', 'used_meter', 'balance_meter',
        'cpiece_meter','actula_balance', 'dpiece_meter', 'short_meter', 'extra_meter'
    ];

    protected $casts = [
        'cu_code' => 'string'
    ];

}
