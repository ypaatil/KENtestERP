<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerticularModel extends Model
{
    use HasFactory;

    protected $table='perticular_master';

    protected $primaryKey = 'perticular_id';
	
	protected $fillable = [
         'perticular_id', 'perticular_name', 'perticular_code', 'userId', 'delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
