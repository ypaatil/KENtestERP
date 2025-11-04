<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialReturnDetailModel extends Model
{
    use HasFactory;

    protected $table='materialReturnDetails';

 
	protected $fillable = [
        'materialReturnCode','materialReturnDate','machine_id','spare_item_code','item_qty','spare_return_material_status_id', 'loc_id'
    ];

    protected $casts = [
        'materialReturnCode' => 'string'
    ];

}
