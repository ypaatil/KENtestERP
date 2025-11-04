<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingDetailModel extends Model
{
    use HasFactory;

    protected $table='cutting_details';
 
	protected $fillable = [
        'cu_code', 'cu_date', 'lot_no', 'job_code', 'style_no', 'Ac_code',
        'table_id', 'table_avg', 'track_code','part_id', 'color_id', 'width', 'shade_id', 'layers',
        'meter', 'size_id', 'qty'
    ];

    protected $casts = [
        'cu_code' => 'string'
    ];

}
