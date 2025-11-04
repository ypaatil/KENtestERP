<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleBarcodeDetailModel extends Model
{
    use HasFactory;

    protected $table='bundle_barcode_details';
 
	protected $fillable = [
        'bb_code', 'bb_date', 'Ac_code', 'job_code','fg_id','style_no', 'task_id', 'cp_id', 'bundle_no', 'roll_track_code',
         'color_id', 'meter', 'bal_meter', 'total_piece', 'layers', 'sizes_array','vpo_code','sales_order_no'
    ];

    protected $casts = [
        'bb_code' => 'string'
    ];


}
