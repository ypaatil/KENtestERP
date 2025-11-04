<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreventiveNameMasterModel extends Model
{
    use HasFactory;

    protected $table='preventive_name_master';
    protected $primaryKey = 'preventive_Id';
	
	protected $fillable = [
        'preventive_name','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}


