<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricTrimCardDetailModel extends Model
{
    use HasFactory;


    protected $table='fabric_trim_card_details';
 
	protected $fillable = [
        'ftc_code', 'ftc_date', 'job_code', 'style_no',  'Ac_code', 'fg_id','color_id', 
         'part_id','width', 'average',  'fabric_image', 'remark'
    ];

    protected $casts = [
        'ftc_code' => 'string'
    ];


}
