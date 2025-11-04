<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGOutletOpeningSizeDetailModel extends Model
{
    use HasFactory;

    protected $table='fg_outlet_opening_size_detail';
 
	protected $fillable = [
         'fgo_code', 'fgo_date', 'Ac_code', 'mainstyle_id', 'color_id', 
         'size_array', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10', 's11', 
         's12', 's13', 's14', 's15', 's16', 's17', 's18', 's19', 's20','s1_rate', 's2_rate', 's3_rate', 's4_rate', 's5_rate', 's6_rate', 's7_rate', 's8_rate', 's9_rate', 's10_rate', 's11_rate', 
         's12_rate', 's13_rate', 's14_rate', 's15_rate', 's16_rate', 's17_rate', 's18_rate', 's19_rate', 's20_rate', 
         's1_barcode', 's2_barcode', 's3_barcode', 's4_barcode', 's5_barcode', 's6_barcode', 's7_barcode', 's8_barcode', 's9_barcode', 's10_barcode', 's11_barcode', 
         's12_barcode', 's13_barcode', 's14_barcode', 's15_barcode', 's16_barcode', 's17_barcode', 's18_barcode', 's19_barcode', 's20_barcode',  'size_qty_total', 'usedFlag','is_opening' 
    ];

    protected $casts = [
        'fgo_code' => 'string'
    ];


}
