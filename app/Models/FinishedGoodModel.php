<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishedGoodModel extends Model
{
    use HasFactory;

    protected $table='fg_master';
    protected $primaryKey = 'fg_id';
	
	protected $fillable = [
       'mainstyle_id','substyle_id', 'fg_name', 'avg_mtr',   'userId', 'created_at', 'updated_at','status'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
    
}
