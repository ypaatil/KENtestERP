<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationDetailModel extends Model
{
    use HasFactory; 

    protected $table='operation_details';
	
	protected $fillable = [
       'operationId', 'operationNameId','operation_rate'
    ];

}
