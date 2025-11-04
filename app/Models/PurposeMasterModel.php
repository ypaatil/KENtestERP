<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurposeMasterModel extends Model
{
    use HasFactory;

    protected $table='purpose_master';
    protected $primaryKey = 'Purpose_ID';
	
	protected $fillable = [
        'Purpose_Name','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
