<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartModel extends Model
{
    use HasFactory;

    protected $table='part_master';
    protected $primaryKey = 'part_id';
	
	protected $fillable = [
        'part_id', 'part_name','userId', 'delflag', 'created_at', 'updated_at' 
    ];

    protected $attributes = [
        'delflag' => 0,
     ];


}
