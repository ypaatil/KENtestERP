<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricOutwardDetailModel extends Model
{
    use HasFactory;

    protected $table='fabric_outward_details';

    protected $primaryKey = 'fout_code';
	
	protected $fillable = [
        'fout_code', 'fout_date','out_type_id','vendorId', 'vpo_code', 'sample_indent_code', 'mainstyle_id', 'substyle_id', 'fg_id', 'style_no', 'style_description','part_id','quality_code',
           'color_id', 'width', 'meter', 'shade_id' ,'track_code' ,'roll_no' ,'item_rate','buyer_id'
    ];

    protected $attributes = [
        'usedflag' => 0,
     ];

     protected $casts = [
        'fout_code' => 'string',
        'track_code' => 'string'
    ];
}
