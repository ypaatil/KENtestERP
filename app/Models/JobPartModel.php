<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPartModel extends Model
{
    use HasFactory;

    protected $table='job_part_master';
    protected $primaryKey = 'jpart_id';
	
	protected $fillable = [
        'jpart_name','jpart_description','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];



}
