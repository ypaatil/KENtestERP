<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricTrimCardMatchDetailModel extends Model
{
    use HasFactory;
    protected $table='fabric_trim_card_match_details';
 
	protected $fillable = [
        'ftc_code', 'ftc_date', 'job_code', 'style_no',  'Ac_code', 'fg_id','body_color_id', 'trim_color_id',
         'part_id','width', 'average',  'fabric_image', 'remark'
    ];

    protected $casts = [
        'ftc_code' => 'string'
    ];
}
