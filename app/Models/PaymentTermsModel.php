<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTermsModel extends Model
{
    use HasFactory;

    protected $table='payment_term';
    protected $primaryKey = 'ptm_id';
	
	protected $fillable = [
        'ptm_name', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
