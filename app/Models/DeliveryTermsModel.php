<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryTermsModel extends Model
{
    use HasFactory;

    protected $table='delivery_terms_master';
    protected $primaryKey = 'dterm_id';
	
	protected $fillable = [
        'delivery_term_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
