<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingMasterModel extends Model
{
    use HasFactory;

    protected $table='cutting_master';

    protected $primaryKey = 'cu_code';
	
	protected $fillable = [
        'cu_code', 'cu_date', 'lot_no', 'job_code', 'style_no', 'Ac_code', 'table_id','table_task_code',
        'table_avg', 'total_pieces','total_layers', 'total_used_meter', 'total_cutpiece_meter','total_actual_balance',
        'total_damage_meter','total_short_meter', 	'total_extra_meter','narration', 'userId', 'delflag', 'created_at', 'updated_at', 'c_code','layer_date','layer_start_time','layer_end_time','cutting_date','cutting_start_time','cutting_end_time'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'cu_code' => 'string'
    ];

}
