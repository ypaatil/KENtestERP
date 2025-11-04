<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationModel extends Model
{
    use HasFactory;

    protected $table='location_master';
    protected $primaryKey = 'loc_id';
	
	protected $fillable = [
        'location','loc_inc', 'gst_no','pan_no', 'userId', 'created_at', 'updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
