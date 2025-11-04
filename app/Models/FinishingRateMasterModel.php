<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingRateMasterModel extends Model
{
    use HasFactory;

    protected $table='finishing_rate_master';

    protected $primaryKey = 'finishing_rate_code';
	
	protected $fillable = [
         'finishing_rate_code', 'Ac_code', 'brand_id','substyle_id', 'userId', 'delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
