<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricTrimCardMasterModel extends Model
{
    use HasFactory;

    protected $table='fabric_trim_card_master';

    protected $primaryKey = 'ftc_code';
	
	protected $fillable = [
       
        'ftc_code', 'ftc_date', 'job_code', 'style_no','fg_id',  'Ac_code','narraiton', 'userId', 'delflag', 'created_at', 'updated_at', 'c_code'

    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'ftc_code' => 'string'
    ];


}
