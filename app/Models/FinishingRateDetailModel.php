<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingRateDetailModel extends Model
{
    use HasFactory;

    protected $table='finishing_rate_details'; 
	
	protected $fillable = [
        'finishing_rate_code','finishing_rate_date','finishing_rate','packing_rate','kaj_button_rate'
    ];
 
}
