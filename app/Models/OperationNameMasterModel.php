<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationNameMasterModel extends Model
{
    use HasFactory; 

    protected $table='operation_name_master';
    protected $primaryKey = 'operationNameId';
	
	protected $fillable = [
       'main_style_id', 'operation_name','delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
         
    ];



}
