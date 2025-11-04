<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WashTypeModel extends Model
{
    use HasFactory;

    protected $table='wash_type_master';
    protected $primaryKey = 'WashTypeId';
    
    protected $fillable = [
        'WashTypeId','WashTypeName','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
