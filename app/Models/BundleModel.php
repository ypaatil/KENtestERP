<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleModel extends Model
{
    use HasFactory;

    protected $table='bundle_barcode_master';

    protected $primaryKey = 'bb_code';
	
	protected $fillable = [
         'bb_date', 'Ac_code', 'job_code', 'cp_id', 'fg_id','vpo_code','sales_order_no',
        'style_no','task_id', 'sizes_array','size_serial_array', 'total_piece','narration', 'userId', 'created_at', 'updated_at'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'bb_code' => 'string'
    ];

}
