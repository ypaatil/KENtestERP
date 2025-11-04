<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserManagement extends Model
{
    use HasFactory;

    protected $table='form_master';
    protected $primaryKey = 'form_code';
	
	protected $fillable = [
        'form_name','form_label','head_id', 'seq_no', 'delflag','created_at','updated_at','cat_id','user_id',
    ];

    protected $attributes = [
        'delflag' => 0,
        
     ];
}

