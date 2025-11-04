<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineModel extends Model
{
    use HasFactory;

    protected $table='line_master';
    protected $primaryKey = 'line_id';
	
	protected $fillable = [
        'Ac_code', 'line_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
