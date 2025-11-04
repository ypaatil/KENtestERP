<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityModel extends Model
{
    use HasFactory;

    protected $table='quality_master';
    protected $primaryKey = 'quality_code';
	
	protected $fillable = [
        'quality_code', 'quality_name', 'userId', 'delflag', 'created_at', 'updated_at' 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];



}
