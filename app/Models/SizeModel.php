<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeModel extends Model
{
    use HasFactory;

    protected $table='size_master';
    protected $primaryKey = 'sz_code';
	
	protected $fillable = [
       'sz_name', 'userId', 'created_at', 'updated_at', 'status'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
