<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transportModel extends Model
{
    use HasFactory;

    protected $table='transport_master';
    protected $primaryKey = 'transport_id';
	
	protected $fillable = [
    'transport_id','transport_name','transport_contact','transport_address','transport_email','gst_number','userId','delflag','created_at','updated_at' ,
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
