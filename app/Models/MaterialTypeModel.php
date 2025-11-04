<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTypeModel extends Model
{
    use HasFactory;


    protected $table='material_type_master';
    protected $primaryKey = 'material_type_id';
	
	protected $fillable = [
        'material_type_name','description', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
