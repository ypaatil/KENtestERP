<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantMasterModel extends Model
{
    use HasFactory;


    protected $table='merchant_master';
    protected $primaryKey = 'merchant_id';
	
	protected $fillable = [
        'merchant_name','contact','email', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
