<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTransferFromInwardDetailModel extends Model
{
    use HasFactory;

    protected $table='materialTransferFromInwardDetails';

 
	protected $fillable = [
        'materialTransferInwardFromCode','materialTransferFromCode', 'materiralInwardCode', 'materialTransferFromInwardDate','spare_item_code','item_qty','stock_qty', 'from_loc_id', 'to_loc_id'
    ];

    protected $casts = [
        'materialTransferInwardFromCode' => 'string'
    ];

}
