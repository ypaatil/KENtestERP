<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatusModel extends Model
{
    use HasFactory;

    protected $table='job_status_master';
    protected $primaryKey = 'job_status_id';
	
	protected $fillable = [
        'job_status_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
