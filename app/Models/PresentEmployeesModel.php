<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentEmployeesModel extends Model
{
    use HasFactory;

    protected $table='presentemployees';
    protected $primaryKey = 'pe_id';
    
    protected $fillable = [
        'pe_date','operators','userId','delflag','created_at','updated_at',
    ];
}
