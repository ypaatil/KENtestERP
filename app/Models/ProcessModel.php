<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessModel extends Model
{
    use HasFactory;

    protected $table='process_master';
    protected $primaryKey = 'process_id';
	
	protected $fillable = [
        'process_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
