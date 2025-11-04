<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerBrandAuthMasterModel extends Model
{
    use HasFactory;

    protected $table='buyer_brand_auth_master';
    protected $primaryKey = 'buyer_brand_auth_id';
    
    protected $fillable = [
         'userId','delflag','created_at','updated_at',
    ];
}
