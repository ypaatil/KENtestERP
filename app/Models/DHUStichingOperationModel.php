<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DHUStichingOperationModel extends Model
{
    use HasFactory;

    protected $table='dhu_stiching_operation';

    protected $primaryKey = 'dhu_so_Id';
	
	protected $fillable = [
         'dhu_so_Name','dhu_so_marathi_Name','userId','created_at','updated_at', 
    ];

}
