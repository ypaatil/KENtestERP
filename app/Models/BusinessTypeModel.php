<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessTypeModel extends Model
{
    use HasFactory;


    protected $table='business_type';
    protected $primaryKey = 'Bt_id';
	
	protected $fillable = [
        'Bt_name','description', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
