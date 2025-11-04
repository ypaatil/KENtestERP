<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderGroupModel extends Model
{
    use HasFactory;

    protected $table='order_group_master';
    protected $primaryKey = 'og_id';
	
	protected $fillable = [
        'order_group_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
