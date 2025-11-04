<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialInwardDetailModel extends Model
{
    use HasFactory;
    
    protected $table='materialInwardDetail';
    
	protected $fillable = ['materiralInwardCode','materiralInwardDate','Ac_code','spare_item_code','hsn_code','unit_id','item_qty','is_opening','location_id', 'item_rate','amount'];
}
