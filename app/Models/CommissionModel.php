<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionModel extends Model
{
    use HasFactory;

    protected $table='commission_master';
    protected $primaryKey = 'coms_id';
	
	protected $fillable = [
        'coms_name','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
