<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricInwardDetailModel extends Model
{
    use HasFactory;

    protected $table='inward_details';

    protected $primaryKey = 'in_code';
	
	protected $fillable = [
        'in_code', 'in_date', 'po_code', 'cp_id', 'Ac_code','item_code', 'part_id','quality_code',
         'roll_no', 'color_id', 'width', 'meter','item_rate','amount',   'shade_id' , 'suplier_roll_no','track_code'   ,'is_opening','location_id', 'buyer_id','fge_code'
    ];

    protected $attributes = [
        'usedflag' => 0,
     ];

     protected $casts = [
        'in_code' => 'string',
        'track_code' => 'string'
    ];


}
