<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcGroupModel extends Model
{
    use HasFactory;


    protected $table='accountgroup';
    protected $primaryKey = 'Group_code';
	
	protected $fillable = [
        'Group_code', 'Group_name', 'Group_main', 'position', 'dc', 'sequence', 'delflag', 'modify_date',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
