<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StyleModel extends Model
{
    use HasFactory;

    protected $table='main_style_master_operation';
    protected $primaryKey = 'mainstyle_id';
    
    protected $fillable = [
        'mainstyle_name','cat_id','sub_cat_id','userId','delflag','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
