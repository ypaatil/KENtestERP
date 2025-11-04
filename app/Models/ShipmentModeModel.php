<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentModeModel extends Model
{
    use HasFactory;

    protected $table='shipment_mode_master';
    protected $primaryKey = 'ship_id';
	
	protected $fillable = [
        'ship_mode_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
