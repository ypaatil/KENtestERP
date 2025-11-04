<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainStyleModel extends Model
{
    use HasFactory;

    protected $table='main_style_master';
    protected $primaryKey = 'mainstyle_id';
	
	protected $fillable = [
        'mainstyle_name','mainstyle_image', 'userId', 'created_at','updated_at','status'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
