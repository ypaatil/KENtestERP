<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerBrandAuthDetailModel extends Model
{
    use HasFactory;

    protected $table='buyer_brand_auth_details';
    
    protected $fillable = [
         'buyer_brand_auth_id','brand_id','auth_id','userId'
    ];
}
