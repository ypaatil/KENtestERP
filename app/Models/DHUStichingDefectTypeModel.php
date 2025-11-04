<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DHUStichingDefectTypeModel extends Model
{
    use HasFactory;

    protected $table='dhu_stiching_defect_type';

    protected $primaryKey = 'dhu_sdt_Id';
	
	protected $fillable = [
         'mainstyle_id','dhu_sdt_Name','dhu_sdt_marathi_Name','userId','created_at','updated_at', 
    ];

}
