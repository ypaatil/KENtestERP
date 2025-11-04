<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassificationModel extends Model
{
    use HasFactory;

    protected $table='classification_master';
    protected $primaryKey = 'class_id';
	
	protected $fillable = [
        'cat_id', 'class_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
