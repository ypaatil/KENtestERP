<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerJobCardSampleDetail extends Model
{
    use HasFactory;

    protected $table='job_card_sample_details';
 
	protected $fillable = [
        'po_code', 'po_date', 'sample_id', 'sample_comp_date', 'sample_tentative_date'
    ];

    protected $casts = [
        'po_code' => 'string'
    ];


}
