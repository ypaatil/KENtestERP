<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationMasterModel extends Model
{
    use HasFactory; 

    protected $table='operation_master';
    protected $primaryKey = 'operationId';
	
	protected $fillable = [
       'main_style_id', 'sales_order_no','delflag', 'created_at', 'updated_at','userId'
    ];

    protected $attributes = [
        'delflag' => 0,
         
    ];



}
