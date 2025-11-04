<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobWorkerModel extends Model
{
    use HasFactory;

    protected $table='job_worker_master';
    protected $primaryKey = 'w_id';
	
	protected $fillable = [
        'w_name', 'w_contact', 'w_address', 'w_particular', 'egroup_id', 'salary_id', 'basic_pay', 'ptm_id', 'day_count', 'dept_id', 'm_id', 'bank_name', 'account_name', 'ac_id', 'account_no', 'ifsc_code', 'userId', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];


  
}
