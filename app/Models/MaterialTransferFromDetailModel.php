<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTransferFromDetailModel extends Model
{
    use HasFactory;

    protected $table='materialTransferFromDetails';

 
	protected $fillable = [
        'materialTransferFromCode','materialTransferFromDate','spare_item_code','materiralInwardCode','item_qty','stock_qty', 'from_loc_id', 'to_loc_id'
    ];

    protected $casts = [
        'materialTransferFromCode' => 'string'
    ];

}
