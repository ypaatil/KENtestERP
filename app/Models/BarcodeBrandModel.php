<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class BarcodeBrandModel extends Model
{
    use HasFactory,LogsActivity;

    protected $table='barcode_brand_masters';
    protected $primaryKey = 'barcode_brand_id';
    
    
	protected $fillable = [
        'barcode_brand_id', 'brand_id','mainstyle_id','barcode_brand_rate', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
