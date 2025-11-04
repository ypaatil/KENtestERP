<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubStyleModel extends Model
{
    use HasFactory;

    protected $table='sub_style_master';
    protected $primaryKey = 'substyle_id';
	
	protected $fillable = [
        'mainstyle_id', 'substyle_name', 'userId', 'created_at','updated_at','status'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
