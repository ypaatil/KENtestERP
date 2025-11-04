<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGOutletOpeningDetailModel extends Model
{
    use HasFactory;

    protected $table='fg_outlet_opening_detail';
 
	protected $fillable = [
        'fgo_code', 'fgo_date', 'Ac_code', 'mainstyle_id', 'color_id', 'size_array', 'rate_array', 'size_qty_array','size_qty_total', 'usedFlag','is_opening' 
    ];

    protected $casts = [
        'fgo_date' => 'string'
    ];


}
