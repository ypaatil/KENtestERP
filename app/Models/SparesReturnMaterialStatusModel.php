<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparesReturnMaterialStatusModel extends Model
{
    use HasFactory;

    protected $table='spare_return_material_status';
    protected $primaryKey = 'spare_return_material_status_id';
	
	protected $fillable = [
        'spare_return_material_status_id','spare_return_material_status_name','delflag','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
