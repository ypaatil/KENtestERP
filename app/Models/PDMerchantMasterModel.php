<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDMerchantMasterModel extends Model
{
    use HasFactory;


    protected $table='PDMerchant_master';
    protected $primaryKey = 'PDMerchant_id';
	
	protected $fillable = [
        'PDMerchant_name','contact','email', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
