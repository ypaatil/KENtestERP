<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KDPLWiseSetPercentageModel extends Model
{
    use HasFactory;

    protected $table='kdpl_wise_set_percentage';
    protected $primaryKey = 'kwspId';
	
	protected $fillable = [
        'sales_order_no','job_status_id','leftover_fabric_value', 'leftover_trims_value','left_pcs_value','rejection_pcs_value', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
