<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOutwardDetailModel extends Model
{
    use HasFactory;

    protected $table='materialoutwarddetails';

 
	protected $fillable = [
        'materialOutwardCode','materialOutwardDate','machine_id','spare_item_code','po_code','item_qty','stock', 'loc_id'
    ];

    protected $casts = [
        'materialOutwardCode' => 'string'
    ];

}
