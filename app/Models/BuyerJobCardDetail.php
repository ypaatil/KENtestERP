<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerJobCardDetail extends Model
{
    use HasFactory;

    protected $table='job_card_details';
 
	protected $fillable = [
        'po_code', 'po_date','Ac_code', 'fg_id','style_no' ,'color_id','sz_code','qty'
    ];

    protected $casts = [
        'po_code' => 'string'
    ];
 
}
