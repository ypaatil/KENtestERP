<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunityModel extends Model
{
    use HasFactory;

    protected $table='opportunity_master';

    protected $primaryKey = 'opportunity_id';
	
	protected $fillable = [
         'opportunity_id', 'opportunity_date', 'Ac_code', 'brand_id','userId', 'delflag', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

}
