<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricDefectModel extends Model
{
    use HasFactory;

    protected $table='fabric_defect_master';
    protected $primaryKey = 'fdef_id';
	
	protected $fillable = [
        'fabricdefect_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
