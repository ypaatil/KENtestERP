<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleBarcodeSerialDetailModel extends Model
{
    use HasFactory;

    protected $table='bundle_barcode_serial_details';
 
	protected $fillable = [
          'bb_code', 'bb_date', 'Ac_code', 'job_code', 'fg_id', 'style_no', 'task_id', 'cp_id', 'size_serial_no','vpo_code','sales_order_no',
         'bundle_id', 'roll_track_code', 'color_id', 'meter', 'bal_meter', 'total_piece', 'layers', 'sizes_id'  
    ];

    protected $casts = [
        'bb_code' => 'string'
    ];


}
