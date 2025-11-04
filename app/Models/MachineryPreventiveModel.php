<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineryPreventiveModel extends Model
{
    use HasFactory;

    protected $table='machinery_preventive_maintance';
    protected $primaryKey = 'preId';
	
	protected $fillable = [
        'preventName_ID','purId','preDate','preDuration','status','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}