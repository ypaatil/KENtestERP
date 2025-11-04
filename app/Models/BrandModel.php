<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class BrandModel extends Model
{
    use HasFactory,LogsActivity;

    protected $table='brand_master';
    protected $primaryKey = 'brand_id';
	
	protected $fillable = [
        'Ac_code', 'brand_name','description', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
