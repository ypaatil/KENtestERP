<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricTrimPartModel extends Model
{


    use HasFactory;

    protected $table='part_master';
    protected $primaryKey = 'part_id';
	
	protected $fillable = [
        'part_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];


}
