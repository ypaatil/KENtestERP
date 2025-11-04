<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model
{
    use HasFactory;

    protected $table='unit_master';
    protected $primaryKey = 'unit_id';
	
	protected $fillable = [
        'unit_name', 'userId', 'created_at', 'updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
