<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DHUDetailModel extends Model
{
    use HasFactory;

    protected $table='dhu_details';

	protected $fillable = [
         'dhu_code','dhu_so_Id','dhu_sdt_Id','defect_qty' 
    ];

}
